<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_Account extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//Load models 
		$this->load->model('Identity_document_type');
		$this->load->model('Civil_status');
		$this->load->model('Disability');
		$this->load->model('Gender');
    }
	
	public function index()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$data['ads_row'] = $this->ads;
		$row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));

		//Get total skills
		$count_skills = $this->Jobseeker_skills->count_jobseeker_skills_by_seeker_id($row->ID);

		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($row->ID);

		$seeker_country = $this->Country->find($row->country);

		$data['title'] = 'Mi perfil - ' . SITE_NAME;
		$data['row'] = $row;
		$data['result_cities'] = $this->City->get_all_cities();
		$data['seeker_country'] = $seeker_country;
		$data['result_countries'] = $this->Country->all(['has_operation_overall' => 1]);
		$data['nationalities'] = $this->Country->all();
		$data['result_ubigeos'] = $this->Ubigeo->get_all_records();
		$data['document_types'] = $this->Identity_document_type->all(['country_id' => $seeker_country->ID, 'active' => 1]);
		$data['civil_status'] = $this->Civil_status->all(['active' => 1]);
		$data['disabilities'] = $this->Disability->all(['active' => 1]);
		$data['genders'] = $this->Gender->all(['active' => 1]);
		$data['count_skills'] = $count_skills;

		$this->form_validation->set_rules('full_name', 'Nombre', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');		
		$this->form_validation->set_rules('document_type', 'Tipo de documento', 'trim|required|in_list_db[tbl_identity_document_types.id]');
		$this->form_validation->set_rules('document_number', 'Número de documento', 'trim|required|integer');
		$this->form_validation->set_rules('full_mobile_phone_number', 'Teléfono móvil', 'trim|required|is_valid_phone_number');
		$this->form_validation->set_rules('full_home_phone_number', 'Teléfono residencial', 'trim|is_valid_phone_number');
		$this->form_validation->set_rules('dob_day', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_month', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_year', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
		$this->form_validation->set_rules('present_address', 'present_address', 'trim|required|max_length[100]|strip_all_tags');
		$this->form_validation->set_rules('country', 'country', 'trim|required|in_list_db[tbl_countries.ID]|strip_all_tags');
		$this->form_validation->set_rules('nationality', 'Nacionalidad', 'trim|required|in_list_db[tbl_countries.ID]|strip_all_tags');
		$this->form_validation->set_rules('city', 'city', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('facebook', 'Facebook', 'trim|strip_all_tags');
		$this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|strip_all_tags');
		$this->form_validation->set_rules('twitter', 'Twitter', 'trim|strip_all_tags');
		
		$check_disability = $this->input->post('check_disability');

		if (isset($check_disability)) {
			$this->form_validation->set_rules('disability', 'Discapacidad', 'trim|required|in_list_db[tbl_disabilities.id]');
		}	
	
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('jobseeker/my_account_view',$data);
			return;
		}
			
		$profile_array = array(
			'first_name'		=> $this->input->post('full_name'),
			'paternal_last_name' => $this->input->post('paternal_last_name'),
			'maternal_last_name' => $this->input->post('maternal_last_name'),
			'last_name' => $this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name'),
			'document_type'     => $this->input->post('document_type'),
			'document_number'   => $this->input->post('document_number'),
			'mobile'			=> trim($this->input->post('full_mobile_phone_number')),
			'home_phone'        => trim($this->input->post('full_home_phone_number')) ? trim($this->input->post('full_home_phone_number')) : '',
			'dob'				=> $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_day'),
			'gender' 	        => $this->input->post('gender'),
			'present_address' 	=> $this->input->post('present_address'),
			'country' 			=> $this->input->post('country'),
			'nationality'       => $this->input->post('nationality'),
			'city' 				=> $this->input->post('city'),
			'civil_status' 		=> $this->input->post('civil_status'),
			'disability' 		=> isset($check_disability) ? $this->input->post('disability') : null,
			'facebook'          => $this->input->post('facebook'),
			'linkedin'          => $this->input->post('linkedin'),
			'twitter'           => $this->input->post('twitter')
		);

		$this->Job_seeker->update($row->ID, $profile_array);
	
		$this->session->set_userdata('first_name',$this->input->post('full_name'));
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Muy bien!</strong> Los datos se guardaron exitosamente. </div>');

		redirect(base_url('jobseeker/my_account'));
	}

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
