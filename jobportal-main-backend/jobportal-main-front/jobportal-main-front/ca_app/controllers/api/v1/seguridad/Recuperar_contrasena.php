<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Recuperar_contrasena extends REST_Controller
{
    public function index_post()
    {
        $user_row = $this->Job_seeker->authenticate_job_seeker_email_address($this->post('email'));
        $ut = 'jobseeker';
		
		if (!$user_row) {
            $this->response([
                'status' => false,
                'message' => 'Email ingresado no existe',
				'data' => []
            ], 200);
            return;
		}

		$this->db->from('tbl_user_password_resets');
		$this->db->where('email', $user_row->email);
		$this->db->where('user_type', $ut);
		
		$token = $this->db->get()->row();

		if ($token) {
			$to_time = strtotime(date('Y-m-d H:i:s'));
			$from_time = strtotime($token->created_at);
			$minute = round(($to_time - $from_time) / 60, 2);
			
			if ($minute < 10) {
                $this->response([
                    'status' => false,
                    'message' => '¡Por favor espera 10 minutos antes de volver a solicitar restablecer tu contraseña!',
					'data' => []
                ], 200);
                return;
            }
		}

        $code = str_pad((mt_rand(1, 99) . mt_rand(10, 99) . mt_rand(10, 99)), 6, "0", STR_PAD_LEFT);
		$user_token = create_token(70, $user_row->email . uniqid(microtime(true)));
       
		$this->db->where('email', $user_row->email);
		$this->db->where('user_type', $ut);
		$this->db->delete('tbl_user_password_resets');

		$data_token = [
			'email' => $user_row->email,
			'user_type' => $ut,
			'token' => $user_token,
            'verification_code' => $code,
			'created_at' => date('Y-m-d H:i:s')
        ];

		$this->db->insert('tbl_user_password_resets', $data_token);
	
		$email = $user_row->email;
		$user_name = $user_row->first_name;
		
		$data_email = [
            'verification_code' => $code,
			'user' => $user_name,
        ];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($email);
		
		$mail_message = load_email_view('email/forgot_password_t2', $data_email);
		
		$this->email->subject('Restablecer contraseña');
		$this->email->message($mail_message);     
		$this->email->send();
		
        $this->response([
            'status' => true,
            'message' => 'Se ha enviado un código de verificación a su correo electrónico',
			'data' => []
        ], 200);
    }
}
