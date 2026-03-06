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
}
