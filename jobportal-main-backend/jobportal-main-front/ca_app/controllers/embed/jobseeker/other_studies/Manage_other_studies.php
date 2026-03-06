<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage_other_studies extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Jobseeker_other_studies');
	}

	public function list($seeker_id)
	{
		$seeker = $this->Job_seeker->find($seeker_id);

		if (!$seeker) {
			show_404();
		}

		$data['seeker'] = $seeker;

		//Other studies
		$data['result_other_studies'] = $this->Jobseeker_other_studies->get_other_studies_by_seeker_id($seeker->ID);
		
		//Get countries
		$data['countries'] = $this->Country->get_all_countries();
		$data['result_degrees_other_studies'] = $this->Qualification->get_all_records_by_val('Otros Estudios');

		$this->load->view('embed/jobseeker/other_studies/manage', $data);
	}
	
	public function add()
	{		
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

		$other_studies_array = [
			'seeker_ID'			=> $this->input->post('seeker_id'),
			'name'		        => $this->input->post('study_name'),
			'type'	            => $this->input->post('type_study'),
			'institute'			=> $this->input->post('institute'),
			'country' 			=> $this->input->post('country'),
			'start_date' 		=> $start_date,
			'end_date' 	        => $end_date,
			'dated'				=> date("Y-m-d H:i:s")
		];

		$this->Jobseeker_other_studies->add($other_studies_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu información ha sido agregada a otros estudios exitosamente.</div>');

		$seeker = $this->Job_seeker->find($this->input->post('seeker_id'));

		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Estudio: </b> " . $this->input->post('study_name') . "<br />";
            $detail.= "<b>Tipo:</b> " . $this->input->post('type_study') . "<br />";
            $detail.= "<b>Instituto:</b> " . $this->input->post('institute') . "<br />";
            $detail.= "<b>País:</b> " . $this->input->post('country');

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Otro estudio agregado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

		echo "done";
	}
	
	public function edit()
	{
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

		$other_studies_array = [
			'name'		        => $this->input->post('study_name'),
			'type'	            => $this->input->post('type_study'),
			'institute'			=> $this->input->post('institute'),
			'country' 			=> $this->input->post('country'),
			'start_date' 		=> $start_date,
			'end_date' 	        => $end_date,
		];
	
		$this->Jobseeker_other_studies->update($this->input->post('otst_id'), $other_studies_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu información de otros estudios ha sido actualizada exitosamente. </div>');
		
		$other_study = $this->Jobseeker_other_studies->get_record_by_id($this->input->post('otst_id'));
		$seeker = $this->Job_seeker->find($other_study['seeker_ID']);

		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Estudio: </b> " . $this->input->post('study_name') . "<br />";
            $detail.= "<b>Tipo:</b> " . $this->input->post('type_study') . "<br />";
            $detail.= "<b>Instituto:</b> " . $this->input->post('institute') . "<br />";
            $detail.= "<b>País:</b> " . $this->input->post('country');

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Otro estudio editado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

		echo "done";
	}
	
	public function delete()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$other_study = $this->Jobseeker_other_studies->get_record_by_id($this->input->post('id'));
		$seeker = $this->Job_seeker->find($other_study['seeker_ID']);

		$this->Jobseeker_other_studies->delete($this->input->post('id'));

		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Estudio: </b> " . $other_study['name'] . "<br />";
            $detail.= "<b>Tipo:</b> " . $other_study['type'] . "<br />";
            $detail.= "<b>Instituto:</b> " . $other_study['institute'] . "<br />";
            $detail.= "<b>País:</b> " . $other_study['country'];

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Otro estudio eliminado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

		echo "done";
	}
	
	public function get_other_study()
	{		
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		$row = $this->Jobseeker_other_studies->get_record_by_id($this->input->post('id'));
		echo json_encode($row);
	}
}
