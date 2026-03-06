<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Config extends CI_Controller {

	public function index()
	{
		$data['title'] ='Configuración - ' . SITE_NAME;
		$data['msg'] = '';

		// if ($_POST) {

		// 	$data_config = array(
		// 		'smtp_host' => $this->input->post('smtp_host') ? $this->input->post('smtp_host', true) : null,
		// 		'smtp_port' => $this->input->post('smtp_port') ? $this->input->post('smtp_port', true) : null,
		// 		'smtp_username' => $this->input->post('smtp_username') ? $this->input->post('smtp_username', true) : null,
		// 		'smtp_password' => $this->input->post('smtp_password') ? $this->input->post('smtp_password', true) : null,
		// 		//'enable_notice_cookies_policy' => $this->input->post('enable_notice_cookies_policy') == '1' ?  '1': '0',
		// 		//'enable_notice_terms' => $this->input->post('enable_notice_terms') == '1' ?  '1': '0',
		// 		//'enable_notice_privacy_policy' => $this->input->post('enable_notice_privacy_policy') == '1' ?  '1': '0'
		// 	);
			
		// 	$this->App_config->save($data_config);
		// 	$this->session->set_flashdata('success_msg', '<div class="message-container"><div class="callout callout-success"><h4>La configuración ha sido actualizada exitosamente.</h4></div></div>');
		// 	redirect('admin/config');
		// 	exit;
		// }

		$this->load->view('admin/config/config', $data);
	}

	public function send_test_email()
    {
    	$success = false;
    	$error = '';
    	
		try {
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
	    	$message = "Prueba de correo electrónico a las " . date('Y-m-d H:i:s');
	    	$this->email->from(ADMIN_EMAIL, SITE_NAME);
	    	$this->email->to($this->input->post('email', true));
			$this->email->subject('Email de prueba');
			$this->email->message($message);

			$success = $this->email->send();

			if ($success === false) {
				$error = $this->email->print_debugger();
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
		}

		echo json_encode([
			'success' => $success,
			'error' => $error 
		]);
    }
}
