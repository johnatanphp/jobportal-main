<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_signup extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		
		//Load models 
		$this->load->model('Identity_document_type');
		$this->load->model('Civil_status');
		$this->load->model('Disability');
		$this->load->model('Gender');

		//Load libraries
        $this->load->library(
            'App/Session/Session_job_seeker', 
            null, 
            'Session_job_seeker'
        );
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		
		if ($this->session->userdata('user_id')) {
			redirect('login');
			exit;
		}
		
		$countries = $this->Country->all(['has_operation_overall' => 1]);

		$visitor = $this->session->userdata('visitor');

		$data['title'] = 'Crear cuenta como postulante - ' . SITE_NAME;
		$data['msg'] = '';
		$data['visitor_country'] = $this->Country->find($visitor['selected_country_id']);
		$data['result_cities'] = $this->City->get_all_cities();
		$data['result_countries'] = $countries;
		$data['nationalities'] = $this->Country->all();
		$data['result_ubigeos'] = $this->Ubigeo->get_all_records();
		$data['departments'] = $this->Ubigeo->get_all_departments();
		$data['document_types'] = $this->Identity_document_type->all(['country_id' => $visitor['selected_country_id'] ?? 0, 'active' => 1]);
		$data['civil_status'] = $this->Civil_status->all(['active' => 1]);
		$data['disabilities'] = $this->Disability->all(['active' => 1]);
		$data['genders'] = $this->Gender->all(['active' => 1]);
		
		$type_register = trim((string) $this->input->post('type_register'));

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_job_seekers.email]|strip_all_tags');	
		
		if ($type_register != '2') {
			$this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[8]|password_strength');
			//$this->form_validation->set_rules('confirm_pass', 'Confirmar contraseña', 'trim|required|matches[pass]');
		}

		$this->form_validation->set_rules('full_name', 'Nombre', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('document_type', 'Tipo de documento', 'trim|required|in_list_db[tbl_identity_document_types.id]');
		$this->form_validation->set_rules('document_number', 'Número de documento', 'trim|required|integer');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
		$this->form_validation->set_rules('dob_day', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_month', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_year', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('country', 'Country', 'trim|required|in_list_db[tbl_countries.ID]|strip_all_tags');	
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|in_list_db[tbl_countries.ID]|strip_all_tags');
		$this->form_validation->set_rules('full_mobile_number', 'Teléfono móvil', 'required|is_valid_phone_number');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|integer');
		$this->form_validation->set_rules('facebook', 'Facebook', 'trim|strip_all_tags');
		$this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|strip_all_tags');
		$this->form_validation->set_rules('twitter', 'Twitter', 'trim|strip_all_tags');
		$this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'trim|required|valid_grecaptcha_invisible');

		if ($this->input->post('country') == '56') { //Peru
			$this->form_validation->set_rules('department', 'Departamento', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('province', 'Provincia', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('district', 'Distritos', 'trim|required|strip_all_tags');
		} else {
			$this->form_validation->set_rules('city', 'Ubicación', 'trim|required|strip_all_tags');
		}

		$check_disability = $this->input->post('check_disability');

		if (isset($check_disability)) {
			$this->form_validation->set_rules('disability', 'Discapacidad', 'trim|required|in_list_db[tbl_disabilities.id]');
		}	

		$this->form_validation->set_message('required', 'El campo %s es requerido');
		$this->form_validation->set_message('is_unique', 'El campo %s ya existe');
		
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		$current_date = date("Y-m-d H:i:s");
		$home_phone = '';

		if ($this->input->post('phone') != '') {
			$home_phone = $this->input->post('home_phone_code')  . ' ' . $this->input->post('phone');
		}
		
		if ($type_register == '2') {
			$password = create_random_password();
		} else {
			$password = $this->input->post('pass');	
		}

		if ($this->form_validation->run() === FALSE ) {
		
			$this->load->library('Facebook/facebook_api_lib');
			$this->load->library('Linkedin/linkedin_api_lib');

			$data['login_url_fb'] = $this->facebook_api_lib->get_login_url();
			$data['login_url_linkedin'] = $this->linkedin_api_lib->get_login_url();
			$data['type_register'] = $type_register;

			$this->load->view('jobseeker_signup_view', $data);
			return;
		}

		$city = $this->input->post('city');

		if ($this->input->post('country') == '56') {

			$ubigeo_data = [
				$this->input->post('department'), 
				$this->input->post('province'),
				$this->input->post('district') 
			];

			$city = join(", ", $ubigeo_data); 
		}

		$job_seeker_array = array(
			'first_name' => $this->input->post('full_name'),
			'paternal_last_name' => $this->input->post('paternal_last_name'),
			'maternal_last_name' => $this->input->post('maternal_last_name'),
			'last_name' => $this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name'),
			'document_number' => $this->input->post('document_number'),
			'document_type' => $this->input->post('document_type'),
			'email' => $this->input->post('email'),
			'password' => do_hashing($password),
			'dob' => $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_day'),
			'mobile' => trim($this->input->post('full_mobile_number')),
			'home_phone' => $home_phone,
			'country' => $this->input->post('country'),
			'city' => $this->input->post('city'),
			'nationality' => $this->input->post('nationality'),
			'gender' => $this->input->post('gender'),
			'ip_address' => $this->input->ip_address(),
			'dated' => $current_date,
			'city' => $city,
			'civil_status' => $this->input->post('civil_status'),
			'disability' => isset($check_disability) ? $this->input->post('disability') : null,
			'facebook' => $this->input->post('facebook'),
			'linkedin' => $this->input->post('linkedin'),
			'twitter' => $this->input->post('twitter')
		);
		
		$seeker_id = $this->Job_seeker->add_job_seekers($job_seeker_array);

		$this->Jobseeker_additional_info->add(array('seeker_ID' => $seeker_id));
		$this->Job_seeker->accept_legal_terms($seeker_id);

		$this->db->insert('tbl_seeker_config', [
			'key' => 'receive_job_ads',
			'value' => $this->input->post('receive_job_ads') == 'true' ? '1' : '0',
			'seeker_ID' => $seeker_id
		]);

		$this->Session_job_seeker->create($this->Job_seeker->find($seeker_id));
		
		$data_email = array(
			'jobseeker_name' => $this->input->post('full_name')
		);
		
		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($this->input->post('email'));
		
		$mail_message = load_email_view('email/registration_jobseeker', $data_email);
		
		$this->email->subject('Registro postulante');
		$this->email->message($mail_message);     
		$this->email->send();
	

		redirect(base_url('jobseeker/add_skills'),'');
	}

    // public function check_recaptcha($recaptcha_response)
    // {
    // 	$this->load->library('recaptcha');

    // 	$response = $this->recaptcha->verify($recaptcha_response);

    // 	if (!$response['success']) {
    // 		$this->form_validation->set_message('check_recaptcha', 'Please enter correct characters.');
    // 		return false;
    // 	}

    // 	return true;
    // }

	public function get_document_types()
	{
		$input_data = $this->input->get();
		$country_id = $input_data['country_id'] ?? 0;
		$results = $this->Identity_document_type->all(['country_id' => $country_id, 'active' => 1]);
		echo json_encode([
			'data' => $results
		]);
	}
}
