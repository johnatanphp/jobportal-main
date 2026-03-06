<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller 
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function forgot()
	{	
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Recuperar mi cuenta - ' . SITE_NAME;
		$data['msg'] = '';
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('user_type', 'Tipo de usuario', 'trim|required');
		$this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'trim|required|valid_grecaptcha');
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		
		if ($this->form_validation->run() === FALSE) {
			$data['user_type'] = $this->input->get('user_type') ? $this->input->get('user_type') : 1;
			$this->load->view('forgot_view', $data);
			return;
		}

		$user_type = $this->input->post('user_type');

		if ($user_type == '1') {
			$user_row = $this->Job_seeker->authenticate_job_seeker_email_address($this->input->post('email'));
			$ut = 'jobseeker';
		}

		if ($user_type == '2') {
			$user_row = $this->Employer->authenticate_by_email($this->input->post('email'));	
			$ut = 'app_user';
		}

		if (!$user_row) {
			$this->session->set_flashdata('msg', '<div class="alert alert-danger">¡La dirección de correo electrónico no existe!</div>');
			redirect('forgot?user_type=' . $user_type);
			exit;
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
				$this->session->set_flashdata('msg', '<div class="alert alert-danger">¡Por favor espera 10 minutos antes de volver a solicitar restablecer tu contraseña!</div>');
				redirect('forgot?user_type=' . $user_type);	
				exit();
			}
		}

		$user_token = create_token(70, $user_row->email . uniqid(microtime(true)));
			
		$this->db->where('email', $user_row->email);
		$this->db->where('user_type', $ut);
		$this->db->delete('tbl_user_password_resets');

		$data_token = array(
			'email' => $user_row->email,
			'user_type' => $ut,
			'token' => $user_token,
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('tbl_user_password_resets', $data_token);
	
		$email = $user_row->email;
		$user_name = $user_row->first_name;
		
		$data_email = array(
			'link_password' => site_url('user/forgot_password/' . $user_token),
			'user' => $user_name,
		);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($email);
		
		$mail_message = load_email_view('email/forgot_password', $data_email);
		
		$this->email->subject('Recuperar contraseña');
		$this->email->message($mail_message);     
		$this->email->send();
		
		$this->session->set_flashdata('msg', '<div class="alert alert-success">Se ha enviado un enlace a el correo electrónico <b>' . $user_row->email . '</b> para que puedas restablecer tu contraseña.</div>');
		
		redirect('forgot?user_type=' . $user_type);		
	}

	public function forgot_password($token = '')
	{
		$this->db->from('tbl_user_password_resets');
		$this->db->where('token', $token);
	
		$token = $this->db->get()->row();

		if (!$token) {
			show_404();
		}

		if ($token) {
		
			$to_time = strtotime($token->created_at);
			$from_time = strtotime('now');
			$minute = round(($from_time - $to_time) / 60, 2);

			if ($minute > 30) {
				$this->session->set_flashdata('msg', '<div class="alert alert-danger">¡Token inválido!</div>');
				redirect(base_url('forgot'));	
				exit();
			}
		}

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Restaurar contraseña - ' . SITE_NAME;
	
		$this->form_validation->set_rules('new_password', 'nueva contraseña', 'trim|required|min_length[8]|password_strength');
		$this->form_validation->set_rules('confirm_password', 'confirmar contraseña', 'trim|required|matches[new_password]');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() == FALSE) {
			$data['token'] = $token;
			$this->load->view('user_forgot_password_view', $data);
			return;
		}

		if ($token->user_type == 'jobseeker') {
	
			$user_data = [
				'password' => do_hashing($this->input->post('new_password')),
				'login_attempts' => 0,
				'blocked_at' => null
			];
			
			$this->db->where('email', $token->email);
			$this->db->update('tbl_job_seekers', $user_data);

			$this->db->where('token', $token->token);
			$this->db->delete('tbl_user_password_resets');

			$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> La contraseña ha sido cambiada con éxito</div>');
			redirect('login');
		}

		if ($token->user_type == 'app_user') {
	
			$user_data = [
				'pass_code' => do_hashing($this->input->post('new_password')),
				'login_attempts' => 0,
				'blocked_at' => null
			];

			$this->db->where('email', $token->email);
			$this->db->update('tbl_employers', $user_data);

			$this->db->where('token', $token->token);
			$this->db->delete('tbl_user_password_resets');

			$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> La contraseña ha sido cambiada con éxito.</div>');
			redirect('employer-login');
		}
	} 
	
	private function register_job_seeker(
		$first_name,
		$last_name, 
		$email, 
		$birthday, 
		$gender, 
		$picture_name = null)
	{
		@list($p_last_name, $m_last_name) = explode(' ', $last_name);

		$p_last_name = empty($p_last_name) ? '' : $p_last_name;
		$m_last_name = empty($m_last_name) ? '' : $m_last_name;
		
		$job_seeker_array = array(
			'first_name' => $first_name,
			'paternal_last_name' => $p_last_name,
			'maternal_last_name' => $m_last_name,
			'last_name' => trim($p_last_name . ' ' . $m_last_name),
			'email' => $email,
			'password' => do_hashing(create_random_password()),
			'dob' => $birthday,
			'mobile' => '',
			'home_phone' => '',
			'present_address' => '',
			'country' => '',
			'city' => '',
			'nationality' => '',
			'gender' => $gender,
			'photo' => !empty($picture_name) ? $picture_name : null,
			'ip_address' => $this->input->ip_address(),
			'dated' => date("Y-m-d H:i:s")
		);

		$seeker_id = $this->Job_seeker->add_job_seekers($job_seeker_array);

		$this->Jobseeker_additional_info->add(array('seeker_ID' => $seeker_id));

		$this->Job_seeker->accept_legal_terms($seeker_id);

	
		return $this->Job_seeker->get_job_seeker_by_id($seeker_id);
	}

	public function show_select_profiles()
	{
		if ($this->session->userdata('is_job_seeker') === true) {
			$this->load->view('jobseeker/modal/modal_show_profile');
		} else if ($this->session->userdata('user_id') && !$this->session->userdata('is_job_seeker')) {
			$user_id = $this->session->userdata('user_id');

			$app_user = $this->db->get_where('tbl_employers', array(
				'ID' => $user_id
			))->row();

			$this->load->model('Employer_profile');

			$profiles = $this->Employer_profile->get_active_profiles($this->session->userdata('user_id'));

			$data['profiles'] = $profiles;
			$this->load->view('common/modal_show_selector_profiles', $data);
		}	
	}

	public function select_profile($profile_id = '')
	{
		$this->load->library(
			'App/Session/Session_employer', 
			null, 
			'Session_employer'
		);

		if (!$this->session->userdata('user_id')) {
			show_404();
		}

		if ($this->session->userdata('is_job_seeker')) {
			show_404();	
		}

		$this->Session_employer->select_profile($profile_id);
		redirect('login');
	}

	public function app_user_change_password()
	{
		if (!$this->session->userdata('user_id') || 
			$this->session->userdata('is_job_seeker') === true) {
			redirect('login');
		}

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Cambiar contraseña - ' . SITE_NAME;
		
		$this->form_validation->set_rules('old_password', 'contraseña actual', 'trim|required');
		$this->form_validation->set_rules('new_password', 'nueva contraseña', 'trim|required|min_length[8]|password_strength');
		$this->form_validation->set_rules('confirm_password', 'confirmar contraseña', 'trim|required|matches[new_password]');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('app_user_change_password_view', $data);
			return;
		}

		$old_password = $this->input->post('old_password');
		
		$user_info = $this->Employer->authenticate_by_password(
			$this->session->userdata('user_id'), 
			$old_password
		);

		if (!$user_info) {
			$this->session->set_flashdata('msg', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error</strong> Contraseña actual no es correcta!</div>');
			redirect('user/app_user_change_password');
		}

		$user_data = [
			'pass_code' => do_hashing($this->input->post('new_password'))
		];
		
		$this->Employer->update($user_info->ID, $user_data);

		$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> La contraseña ha sido cambiada con éxito.</div>');
		redirect('user/app_user_change_password');	
	}

	public function gp()
	{
		//echo do_hashing('');
	}
}
