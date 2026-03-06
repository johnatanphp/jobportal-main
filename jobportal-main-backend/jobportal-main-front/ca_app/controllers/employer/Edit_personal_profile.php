<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_Personal_Profile extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME.' : Editar perfil';
		$data['msg']='';
		$data['result_cities'] = $this->City->get_all_cities();
		$data['result_countries'] = $this->Country->get_all_countries();
		$data['result_industries'] = $this->Industry->get_all_industries();
		$row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		$data['row'] = $row;
		$this->form_validation->set_rules('full_name', 'Nombre', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_day', 'día de nacimiento', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_month', 'mes de nacimiento', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_year', 'año de nacimiento', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('city', 'ubicación / ciudad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('mobile_phone', 'teléfono móvil', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('employer/edit_personal_profile_view',$data);
			return;
		}
		$employer_array = array(
								'first_name' => $this->input->post('full_name'),							
								'dob' => $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_day'),
								'mobile_phone' => $this->input->post('mobile_phone'),
								'home_phone' => $this->input->post('home_phone'),
								'country' => $this->input->post('country'),
								'city' => $this->input->post('city')
		);
		$this->Employer->update_employer($row->ID, $employer_array);
		$this->session->set_userdata('first_name',$this->input->post('full_name'));
		$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> Tu información personal ha sido actualizada.</div>');
		redirect(base_url('employer/edit_personal_profile'));
	}
	
}
