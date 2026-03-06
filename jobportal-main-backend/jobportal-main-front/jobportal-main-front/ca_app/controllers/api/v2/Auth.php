<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_base_Controller.php';

class Auth extends Api_v2_base_Controller
{
    public function login_post()
    {   
        $params = $this->post();

        $email = isset($params['username']) ? trim($params['username']) : '';
        $password = isset($params['password']) ? trim($params['password']) : '';
        
        $this->load->library('App/Auth/Auth_employer_login', null, 'Auth_employer_login');
        $auth_response = $this->Auth_employer_login->login($email, $password);

        if ($auth_response['success'] === false) {
            $this->response(
                apiv2_response(
                    false, 
                    $auth_response['message']
                ),
                200
            );
            return;
        }

        $employer_id = isset($auth_response['data']['user_id']) ? $auth_response['data']['user_id'] : null;

        $access_token = $this->get_token($employer_id);

        if (!$access_token) {
            $this->response(apiv2_response(false, 'Error al obtener el token'), 500);
            return;
        }

        $response_data = [
            'access_token' => $access_token,
            //'token_type' => 'bearer'
        ];

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }

    private function get_token($employer_id)
    {
        $access_token = bin2hex(random_bytes(18));
        $date = new DateTime('now');
        // Sumar 1 hora
        $date->add(new DateInterval('PT1H'));
        $expires_at = $date->format('Y-m-d H:i:s');

        $trans_updated = $this->db->insert('tbl_api_user_tokens', [
            //'app_user_id' => null,
            'user_id' => $employer_id,
            'user_type' => 'employer',
            'access_token' => $access_token,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $token_id = $this->db->insert_id();

        if (!$token_id) {
            return false;
        }

        return $access_token;
    }

    public function logout_post()
    {
        $post = $this->post();

        $token = $post['token'] ?? '';

        $this->db->from('tbl_api_user_tokens');
        $this->db->where('access_token', $token);
        $this->db->where('revoked', 0);
        $token_row = $this->db->get()->row();

        if (!$token_row) {
            $this->response(apiv2_response(false, 'Token inválido'), 400);
            return;
        }

        $this->db->where('id', $token_row->id);
        $this->db->update('tbl_api_user_tokens', [
            'revoked' => 1,
            'revoked_at' => date('Y-m-d H:i:s')
        ]);

        $this->response(apiv2_response(true, 'Token revocado'), 200);
    }
    
    public function token_post()
    {
        $post = $this->post();

        $code = $post['code'] ?? '';
        $redirect_uri = $post['redirect_uri'] ?? '';
        $grant_type = $post['grant_type'] ?? '';
        
        if (!in_array($grant_type, ['authorization_code'])) {
            $this->response(apiv2_response(false, 'unsupported_grant_type: debe ingresar un tipo de grant_type válido'), 400);
            return;  
        }
        
        $this->db->from('tbl_api_user_auth_codes');
        $this->db->where('code', $code);
        $code_row = $this->db->get()->row();

        if (!$code_row || $code_row->is_used == 1) {
            $this->response(apiv2_response(false, 'invalid_grant: Codigo de autorización ya fue usado o no existe'), 400);
            return;
        }

        if ($redirect_uri != $code_row->redirect_uri) {
            $this->response(apiv2_response(false, 'invalid_grant: redirect_uri no coincide con la usada en el paso de autorización'), 400);
            return;
        }
        
        if ($code_row->code_challenge) {
            
            $code_verifier = $post['code_verifier'] ?? '';
            
            $hash_binario = hash('sha256', $code_verifier, true);
            
            $base64_estandar = base64_encode($hash_binario);
            
            // Transformaciones a Base64 URL-Safe:
            // Reemplazar '+' por '-'
            $base64_url_safe = strtr($base64_estandar, '+', '-');
            
            // Reemplazar '/' por '_'
            $base64_url_safe = strtr($base64_url_safe, '/', '_');
            
            // Eliminar el padding '=' al final
            $auth_code_code_challenge = rtrim($base64_url_safe, '=');
            
            $code_challenge = $code_row->code_challenge;
            
            if ($auth_code_code_challenge != $code_challenge) {
                $this->response(apiv2_response(false, 'invalid_grant: El valor del code_verifier proporcionado no genera el mismo code_challenge que se recibió inicialmente en el paso de autorización.'), 400);
                return;
            }
        }
        
        $date_now = new DateTime('now');
        $date_expires = new DateTime($code_row->expires_at);
        
        // Verificar si el codigo ha expirado
        if ($date_now->getTimestamp() > $date_expires->getTimestamp()) {
            $this->response(apiv2_response(false, 'invalid_grant: Codigo de autorización expirado'), 400);
            return;
        }
        
        $access_token = $this->get_token($code_row->user_id);

        if (!$access_token) {
            $this->response(apiv2_response(false, 'invalid_grant: Error al generar el token'), 500);
            return;
        }
        
        $this->db->where('code', $code_row->code);
        $this->db->update('tbl_api_user_auth_codes', [
            'is_used' => 1,
            'access_token' => $access_token
        ]);
        
        $response_data = [
            'access_token' => $access_token
        ];
        
        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
