<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Experience extends CI_Controller {
	public function index(){
		echo "you are not allow to access this page directly";
		exit;
	}
	
	public function add()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}		

		$this->form_validation->set_rules('job_title', 'puesto', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('company_name', 'empresa', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_job_level', 'nivel del puesto', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_area', 'área', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_industry', 'industria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_start_month', 'mes de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_start_year', 'año de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('exp_description', 'descripción de la experiencia', 'trim|strip_all_tags');
		
		$working = $this->input->post('exp_working') == "true";
		
		if (!$working) {
			$this->form_validation->set_rules('exp_completion_month', 'mes de la fecha final', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('exp_completion_year', 'año de la fecha final', 'trim|required|strip_all_tags');
		}

		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$start_date = $this->input->post('exp_start_year') . "-" . $this->input->post('exp_start_month') . '-01';
		$end_date = null;

		if (!$working) {
			$end_date = $this->input->post('exp_completion_year') . "-" . $this->input->post('exp_completion_month') . '-01';
		}

		$exp_array = array(
			'seeker_ID'		=> $this->session->userdata('user_id'),
			'job_title'		=> $this->input->post('job_title'),
			'company_name'	=> $this->input->post('company_name'),
			'country'		=> $this->input->post('exp_country'),
			'start_date' 	=> $start_date,
			'end_date' 		=> $end_date,
			'industry'      => $this->input->post('exp_industry'),
			'job_level'     => $this->input->post('exp_job_level'),
			'area'          => $this->input->post('exp_area'),
			'description'   => $this->input->post('exp_description'),
			'dated'			=> date("Y-m-d H:i:s")
		);
		
		$this->Jobseeker_experience->add($exp_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu experiencia ha sido agregada exitosamente. </div>');
		echo "done";
	}
	
	public function edit()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('ed_job_title', 'puesto', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_company_name', 'empresa', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_job_level', 'nivel del puesto', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_area', 'área', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_industry', 'industria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_start_month', 'mes de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_start_year', 'año de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_exp_description', 'descripción de la experiencia', 'trim|strip_all_tags');
		
		$working = $this->input->post('ed_exp_working') == "true";
 		
 		if (!$working) {
			$this->form_validation->set_rules('ed_exp_completion_month', 'mes de la fecha final', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('ed_exp_completion_year', 'año de la fecha final', 'trim|required|strip_all_tags');
		}

		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$start_date = $this->input->post('ed_exp_start_year') . "-" . $this->input->post('ed_exp_start_month') . '-01';
		$end_date = null;

		if (!$working) {
			$end_date = $this->input->post('ed_exp_completion_year') . "-" . $this->input->post('ed_exp_completion_month') . '-01';
		}
	
		$exp_array = array(
			'job_title'		=> $this->input->post('ed_job_title'),
			'company_name'	=> $this->input->post('ed_company_name'),
			'country'		=> $this->input->post('ed_exp_country'),
			'industry'      => $this->input->post('ed_exp_industry'),
			'job_level'     => $this->input->post('ed_exp_job_level'),
			'area'          => $this->input->post('ed_exp_area'),
			'description'   => $this->input->post('ed_exp_description'),
			'start_date' 	=> $start_date,
			'end_date' 		=> $end_date
		);
		
		$this->Jobseeker_experience->update($this->input->post('ed_exp_id'), $exp_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu experiencia ha sido actualizada con éxito. </div>');
		echo "done";
	}
	
	public function delete()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}
		$this->Jobseeker_experience->delete($this->input->post('id'));
		echo "done";
	}
	
	public function experience_by_id()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
		
		$row = $this->Jobseeker_experience->get_record_by_id($this->input->post('id'));
		echo json_encode($row);
	}
}
