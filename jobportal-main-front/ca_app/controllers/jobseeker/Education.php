<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Education extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Institution_educational_type');
		$this->load->model('Institution_educational_class');
		$this->load->model('Institution_type');
		$this->load->model('Institution');
		$this->load->model('Career');
	}

	public function index()
	{
		echo "you are not allow to access this page directly";
		exit;
	}
	
	public function add()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('degree_title', 'grado', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('major_subject', 'Titulo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('edu_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('month_start_date', 'mes de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('year_start_date', 'año de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution_educ_type', 'Regimen', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution_educ_class', 'Clase', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution_type', 'Tipo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution', 'Institucion', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('career', 'Carrera', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('tuition_number', 'Nro. colegiatura', 'trim|strip_all_tags');

		$studying = $this->input->post('studying') == "true";

		if (!$studying) {
			$this->form_validation->set_rules('month_end_date', 'mes de la fecha de finalización', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('year_end_date', 'año de la fecha de finalización', 'trim|required|strip_all_tags');		
		}

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'success' => false,
				'message' => validation_errors()
			]);
			exit;
		}

		$start_date = $this->input->post('year_start_date') . "-" . $this->input->post('month_start_date') . '-01';
		$end_date = null;

		if (!$studying) {
			$end_date = $this->input->post('year_end_date') . "-" . $this->input->post('month_end_date') . '-01';
			
			if (strtotime($start_date) >= strtotime($end_date)) {
				echo json_encode([
					'success' => false,
					'message' => 'Fecha de fin debe ser mayor a la fecha de inicio'
				]);
				exit;
			}
		}
		
		$edu_array = array(
			'seeker_ID' => $this->session->userdata('user_id'),
			'degree_title' => $this->input->post('degree_title'),
			'major' => $this->input->post('major_subject'),
			'country'  => $this->input->post('edu_country'),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'dated' => date("Y-m-d H:i:s"),
			'institution_educational_type_id' => $this->input->post('institution_educ_type'),
			'institution_educational_class_id' => $this->input->post('institution_educ_class'),
			'institution_type_id' => $this->input->post('institution_type'),
			'institution' => $this->input->post('institution'),
			'career' => $this->input->post('career'),
			'tuition_number' => $this->input->post('tuition_number')	
		);

		$this->Jobseeker_academic->add($edu_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu educación ha sido agregada exitosamente. </div>');
		
		echo json_encode([
			'success' => true,
			'message' => 'Estudio agregado'
		]);
	}
	
	public function edit()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('ed_edu_id', 'ID', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_degree_title', 'grado', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_major_subject', 'carrera', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('ed_edu_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('month_start_date', 'mes de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('year_start_date', 'año de la fecha de inicio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution_educ_type', 'Regimen', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution_educ_class', 'Clase', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution_type', 'Tipo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('institution', 'Institucion', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('career', 'Carrera', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('tuition_number', 'Nro. colegiatura', 'trim|strip_all_tags');
	
		$studying = $this->input->post('ed_studying') == "true"; 

		if (!$studying) {
			$this->form_validation->set_rules('month_end_date', 'mes de la fecha de finalización', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('year_end_date', 'año de la fecha de finalización', 'trim|required|strip_all_tags');
		}

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'success' => false,
				'message' => validation_errors()
			]);
			exit;
		}
		
		$start_date = $this->input->post('year_start_date') . "-" . $this->input->post('month_start_date') . '-01';
		$end_date = null;

		if (!$studying) {
			$end_date = $this->input->post('year_end_date') . "-" . $this->input->post('month_end_date') . '-01';

			if (strtotime($start_date) >= strtotime($end_date)) {
				echo json_encode([
					'success' => false,
					'message' => 'Fecha de fin debe ser mayor a la fecha de inicio'
				]);
				exit;
			}
		}

		$edu_array = array(
			'degree_title' => $this->input->post('ed_degree_title'),
			'major' => $this->input->post('ed_major_subject'),
			'institude' => $this->input->post('ed_institute'),
			'country' => $this->input->post('ed_edu_country'),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'institution_educational_type_id' => $this->input->post('institution_educ_type'),
			'institution_educational_class_id' => $this->input->post('institution_educ_class'),
			'institution_type_id' => $this->input->post('institution_type'),
			'institution' => $this->input->post('institution'),
			'career' => $this->input->post('career'),
			'tuition_number' => $this->input->post('tuition_number')
		);

		$this->Jobseeker_academic->update($this->input->post('ed_edu_id'), $edu_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu educación ha sido actualizada exitosamente. </div>');
		
		echo json_encode([
			'success' => true,
			'message' => 'Estudio agregado'
		]);
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
		$this->Jobseeker_academic->delete($this->input->post('id'));
		echo "done";
	}
	
	public function education_by_id()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		$row = $this->Jobseeker_academic->get_record_by_id($this->input->post('id'));
		echo json_encode($row);
	}

	public function edit_form()
	{
		$seeker_academic = $this->Jobseeker_academic->find($this->input->post('id'));
		$data['seeker_academic'] = $seeker_academic;
		$data['result_degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['result_countries'] = $this->Country->get_all_countries();
		$data['result_institution_educ_types'] = $this->Institution_educational_type->all(['active' => 1]);
		$data['result_institution_educ_class'] = $this->Institution_educational_class->all(['active' => 1]);
		$data['result_institution_types'] = $this->Institution_type->all(['active' => 1]);
		$data['result_institutions'] = $this->Institution->all([
			'active' => 1, 
			'institution_educational_type_id' => $seeker_academic->institution_educational_type_id, 
			'institution_type_id' => $seeker_academic->institution_type_id
		]);
		$data['result_careers'] = $this->Career->get_all_by_institution_code($seeker_academic->institution);

		$this->load->view('jobseeker/common/modal_education_edit', $data);
	}
}
