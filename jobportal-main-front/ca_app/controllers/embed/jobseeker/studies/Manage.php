<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();

		//Load models
		$this->load->model('Institution_educational_type');
		$this->load->model('Institution_educational_class');
		$this->load->model('Institution_type');
		$this->load->model('Institution');
		$this->load->model('Career');

		//Load library
		$this->load->library('storage_lib', null, 'Storage_lib');
    }
	
	public function list($seeker_id = 0)
	{
		$job_mof = null;
		$row = $this->Job_seeker->find($seeker_id);

		$job_desc = trim((string)$this->input->get('job_description'));

		//$job_desc = 'Analista Android';
		
		if ($job_desc != '' && $job_desc != '-') {
			$job_mof = $this->search_mof_job($job_desc);
		}

        if (!$row) {
            show_404();
        }
		//Qualification
		$result_qualification = $this->Job_seeker->get_qualification_by_jobseeker_id($row->ID);

        $data['seeker'] = $row;
        $data['countries'] = $this->Country->get_all_countries();
		$data['studies'] = $result_qualification;
        $data['degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['education_min_detail'] = @$job_mof->education_min_detail;
		$data['education_min'] = $this->Qualification->find(@$job_mof->study_grade_min);
		$data['institution_educ_types'] = $this->Institution_educational_type->all(['active' => 1]);
		$data['institution_educ_class'] = $this->Institution_educational_class->all(['active' => 1]);
		$data['institution_types'] = $this->Institution_type->all(['active' => 1]);

		$this->load->view('embed/jobseeker/studies/manager',$data);
	}

    public function add()
	{
		$seeker = $this->Job_seeker->find($this->input->post('seeker_id'));

		$this->form_validation->set_rules('degree_title', 'grado', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('major_subject', 'carrera', 'trim|required|strip_all_tags');
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
			'seeker_ID' => $this->input->post('seeker_id'),
			'degree_title' => $this->input->post('degree_title'),
			'major' => $this->input->post('major_subject'),
			'institude' => $this->input->post('institute'),
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
		
		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Nivel académico: </b> " . $this->input->post('degree_title') . "<br />";
            $detail.= "<b>Título:</b> " . $this->input->post('major_subject') . "<br />";
            $detail.= "<b>País:</b> " . $this->input->post('edu_country');

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Estudio Agregado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

		echo json_encode([
			'success' => true,
			'message' => 'Estudio agregado'
		]);
	}
	
	public function edit()
	{
		$seeker_estudy = $this->Jobseeker_academic->find($this->input->post('ed_edu_id'));
        $seeker = $this->Job_seeker->find($seeker_estudy->seeker_ID);

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
		
		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Nivel académico: </b> " . $seeker_estudy->degree_title . "<br />";
            $detail.= "<b>Título:</b> " . $seeker_estudy->major . "<br />";
            $detail.= "<b>País:</b> " . $seeker_estudy->country;

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Estudio Editado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}
		
		echo json_encode([
			'success' => true,
			'message' => 'Estudio editado'
		]);
	}
	
	public function delete()
	{		
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$seeker_estudy = $this->Jobseeker_academic->find($this->input->post('id'));
        $seeker = $this->Job_seeker->find($seeker_estudy->seeker_ID);

		$this->Jobseeker_academic->delete($this->input->post('id'));

		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Estudio grado: </b> " . $seeker_estudy->degree_title . "<br />";
            $detail.= "<b>Instituto:</b> " . $seeker_estudy->institude . "<br />";
            $detail.= "<b>Carrera:</b> " . $seeker_estudy->major . "<br />";
            $detail.= "<b>País:</b> " . $seeker_estudy->country;

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Estudio eliminado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

		echo "done";
	}
	
	public function education_by_id()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		$row = $this->Jobseeker_academic->get_record_by_id($this->input->post('id'));
		echo json_encode($row);
	}

	public function send_study_certificate()
    {
        $data['title'] = 'Certificados de estudios - ' . SITE_NAME;
        $data['ads_row'] = $this->ads;

        $job_seeker_id = $this->session->userdata('user_id');
        $result_studies = $this->Job_seeker->get_qualification_by_jobseeker_id($job_seeker_id);        
        $data['result_studies'] = $result_studies;        

        $this->load->view('jobseeker/requested_documents/send_study_certificate', $data);
    }

    public function upload_certificates()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
			'image/jpeg',
			'image/png',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        $study_id = $this->input->post('study_id');

        $file_previus = $this->Jobseeker_academic->find(
            $study_id
        );
        $seeker_id = $file_previus->seeker_ID;

		$seeker = $this->Job_seeker->find($seeker_id);

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/study_certificates/' .  md5(uniqid($study_id . $seeker_id, true)) . $file_ext;
            
            $path = $this->Storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->Storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Jobseeker_academic->save_certificate(
            $study_id, 
            $seeker_id, 
            $path
        );

        if (!$trans_status) {
            exit(json_encode(
                ['error' => 'Error al guardar el documento']
            ));
        }

        if ($file_previus && 
            $file_previus->attached_certificate &&
            $this->Storage_lib->has($file_previus->attached_certificate)) {
            $this->Storage_lib->delete($file_previus->attached_certificate);
        }

        $file_url = file_url($path);

		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Estudio grado: </b> " . $file_previus->degree_title . "<br />";
            $detail.= "<b>Instituto:</b> " . $file_previus->institude . "<br />";
            $detail.= "<b>Carrera:</b> " . $file_previus->major . "<br />";
            $detail.= "<b>País:</b> " . $file_previus->country . "<br />";
			$detail.= "<b>Certificado: </b><a href='" . $file_url . "'>Ver</a>";
			
			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Certificado de estudio cargado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

	private function search_mof_job($job_desc = '')
	{
		$this->db->from('tbl_mofs');
		$this->db->where('active', 1);
		$this->db->where('(job_title="' .  $job_desc . '" OR job_title like "%' . $job_desc . '%")');
		return $this->db->get()->row();
	}

	public function edit_form()
	{
		$seeker_academic = $this->Jobseeker_academic->find($this->input->post('id'));
		$data['seeker_academic'] = $seeker_academic;
		$data['degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['countries'] = $this->Country->get_all_countries();
		$data['institution_educ_types'] = $this->Institution_educational_type->all(['active' => 1]);
		$data['institution_educ_class'] = $this->Institution_educational_class->all(['active' => 1]);
		$data['institution_types'] = $this->Institution_type->all(['active' => 1]);
		$data['institutions'] = $this->Institution->all([
			'active' => 1, 
			'institution_educational_type_id' => $seeker_academic->institution_educational_type_id, 
			'institution_type_id' => $seeker_academic->institution_type_id
		]);
		$data['careers'] = $this->Career->get_all_by_institution_code($seeker_academic->institution);

		$this->load->view('embed/jobseeker/studies/common/edit_study', $data);
	}
}
