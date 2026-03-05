<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_update_entry extends CI_Controller
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
		$data['ads_row'] = $this->ads;
		$token = trim($this->input->get('t') ? $this->input->get('t') : $this->input->post('t'));

		$this->db->select([
			'seeker_id'
		]);
		$this->db->from('tbl_seeker_entries');
		$this->db->where('entry_key', $token);
		$seeker_entry = $this->db->get()->row();

		if (!$seeker_entry || !$seeker_entry->seeker_id) {
			show_404();
		}
		
		$seeker = $this->Job_seeker->find($seeker_entry->seeker_id);

		if (!$seeker) {
			show_404();
		}
		
		$data['title'] = 'Actualizar datos - ' . SITE_NAME;
		$data['row'] = $seeker;
		$data['result_cities'] = $this->City->get_all_cities();
		$data['result_countries'] = $this->Country->all();
		$data['result_ubigeos'] = $this->Ubigeo->get_all_records();
		$data['document_types'] = $this->Identity_document_type->all();
		$data['civil_status'] = $this->Civil_status->all(['active' => 1]);
		$data['disabilities'] = $this->Disability->all(['active' => 1]);
		$data['genders'] = $this->Gender->all(['active' => 1]);
		$data['token'] = $token;
	
		$this->form_validation->set_rules('full_name', 'Nombre', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');		
		$this->form_validation->set_rules('document_type', 'Tipo de documento', 'trim|required|in_list_db[tbl_identity_document_types.id]');
		$this->form_validation->set_rules('document_number', 'Número de documento', 'trim|required|integer');
		$this->form_validation->set_rules('mobile', 'mobile', 'trim|required|integer');
		$this->form_validation->set_rules('home_phone', 'Teléfono residencial', 'trim|integer');
		$this->form_validation->set_rules('dob_day', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_month', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_year', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
		$this->form_validation->set_rules('present_address', 'present_address', 'trim|required|strip_all_tags');
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
			$this->load->view('jobseeker/jobseeker_update_entry_view', $data);
			return;
		}

		$home_phone = '';
		
		if ($this->input->post('home_phone') != '') {
			$home_phone = $this->input->post('home_phone_code')  . ' ' . $this->input->post('home_phone');
		}
		
		$profile_array = [
			'first_name' => $this->input->post('full_name'),
			'paternal_last_name' => $this->input->post('paternal_last_name'),
			'maternal_last_name' => $this->input->post('maternal_last_name'),
			'last_name' => $this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name'),
			'document_type'     => $this->input->post('document_type'),
			'document_number'   => $this->input->post('document_number'),
			'mobile'			=> $this->input->post('mobile_phone_code') . ' ' . $this->input->post('mobile'),
			'home_phone'        => $home_phone,
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
		];

		$this->Job_seeker->update($seeker->ID, $profile_array);
	
		$this->session->set_flashdata(
			'msg', 
			'<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Muy bien!</strong> Los datos se guardaron exitosamente. </div>'
		);

		redirect('login');
	}
}
