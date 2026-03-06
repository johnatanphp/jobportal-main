<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Other_studies extends CI_Controller {
	public function index(){
		echo "you are not allow to access this page directly";
		exit;
	}
	
	public function add()
	{
		if (!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('study_name', 'nombre del estudio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('type_study', 'tipo de estudio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institute', 'institución', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('start_month', 'mes de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('start_year', 'año de la fecha de inicio', 'trim|required|strip_all_tags');
		
		$studying = $this->input->post('studying') == "true"; 

		if (!$studying) {
			$this->form_validation->set_rules('completion_month', 'mes de la fecha de finalización', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('completion_year', 'año de la fecha de finalización', 'trim|required|strip_all_tags');
		}

		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$start_date = $this->input->post('start_year') . "-" . $this->input->post('start_month') . '-01';
		$end_date = null;


		if (!$studying) {
			$end_date = $this->input->post('completion_year') . "-" . $this->input->post('completion_month') . '-01';
		}

		$other_studies_array = array(
			'seeker_ID'			=> $this->session->userdata('user_id'),
			'name'		        => $this->input->post('study_name'),
			'type'	            => $this->input->post('type_study'),
			'institute'			=> $this->input->post('institute'),
			'country' 			=> $this->input->post('country'),
			'start_date' 		=> $start_date,
			'end_date' 	        => $end_date,
			'dated'				=> date("Y-m-d H:i:s")
		);

		$this->Jobseeker_other_studies->add($other_studies_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu información ha sido agregada a otros estudios exitosamente.</div>');
		
		echo "done";
	}
	
	public function edit()
	{
		if (!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('study_name', 'nombre del estudio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('type_study', 'tipo de estudio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institute', 'institución', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('start_month', 'mes de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('start_year', 'año de la fecha de inicio', 'trim|required|strip_all_tags');
		
		$studying = $this->input->post('studying') == "true"; 

		if (!$studying) {
			$this->form_validation->set_rules('completion_month', 'mes de la fecha de finalización', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('completion_year', 'año de la fecha de finalización', 'trim|required|strip_all_tags');
		}

		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$start_date = $this->input->post('start_year') . "-" . $this->input->post('start_month') . '-01';
		$end_date = null;

		$studying = $this->input->post('studying') == "true"; 

		if (!$studying) {
			$end_date = $this->input->post('completion_year') . "-" . $this->input->post('completion_month') . '-01';
		}

		$other_studies_array = array(
			'name'		        => $this->input->post('study_name'),
			'type'	            => $this->input->post('type_study'),
			'institute'			=> $this->input->post('institute'),
			'country' 			=> $this->input->post('country'),
			'start_date' 		=> $start_date,
			'end_date' 	        => $end_date,
		);
	
		$this->Jobseeker_other_studies->update($this->input->post('otst_id'), $other_studies_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu información de otros estudios ha sido actualizada exitosamente. </div>');
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
		$this->Jobseeker_other_studies->delete($this->input->post('id'));
		echo "done";
	}
	
	public function get_other_study()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		$row = $this->Jobseeker_other_studies->get_record_by_id($this->input->post('id'));
		echo json_encode($row);
	}
}
