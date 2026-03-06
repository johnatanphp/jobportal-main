<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Cambiar_contrasena extends REST_Controller
{
    public function index_post()
    {   
        $password = trim($this->post('contrasena'));

        if (strlen($password) <= 7 || 
            strlen($password) >= 13) {
            $this->response([
                'status' => false,
                'message' => 'La contraseña debe contener como mínimo 8 y máximo 12 caracteres',
                'data' => []
            ], 200);
            return;
        }

        $this->db->from('tbl_user_password_resets');
		$this->db->where('user_type', 'jobseeker');
        $this->db->where('email', $this->post('email'));
        $this->db->where('verification_code', $this->post('codigo'));

		$token = $this->db->get()->row();

		if (!$token) {
            $this->response([
                'status' => false,
                'message' => 'Los datos ingresados no son válidos.',
                'data' => []
            ], 200);
            return;
		}
		
        $to_time = strtotime($token->created_at);
        $from_time = strtotime('now');
        $minute = round(($from_time - $to_time) / 60, 2);

        if ($minute > 120) {
            $this->response([
                'status' => false,
                'message' => 'EL código de vereficación ha caducado',
                'data' => []
            ], 200);
            return;
        }
		
        $user_data = [
            'password' => do_hashing($password),
            'login_attempts' => 0,
            'blocked_at' => null
        ];
        
        $this->db->where('email', $token->email);
        $this->db->update('tbl_job_seekers', $user_data);

        $this->db->where('email', $token->email);
        $this->db->delete('tbl_user_password_resets');

        $this->response([
            'status' => true,
            'message' => 'La contraseña ha sido cambiada con éxito.',
            'data' => []
        ], 200);
    }
}
