<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage_experiences extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        $this->load->library('storage_lib', null, 'Storage_lib');
    }

    public function list($seeker_id = 0)
    {
        $row = $this->Job_seeker->find($seeker_id);

        if (!$row) {
            show_404();
        }

        //Experience
        $result_experience = $this->Job_seeker->get_experience_by_jobseeker_id($row->ID);

        $data['seeker'] = $row;
        $data['countries'] = $this->Country->get_all_countries();
        //$data['degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');

        $data['result_experience'] = $result_experience;
        $data['result_industries'] = $this->Industry->get_all_industries();
        
        $this->load->view('embed/jobseeker/experiences/manager',$data);
    }
    
    public function add()
    {
        $seeker = $this->Job_seeker->find($this->input->post('seeker_id'));

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
            'seeker_ID'		=> $this->input->post('seeker_id'),
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
        
        try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Experiencia laboral: </b> " . $this->input->post('job_title') . "<br />";
            $detail.= "<b>Empresa:</b> " . $this->input->post('company_name') . "<br />";
            $detail.= "<b>Puesto:</b> " . $this->input->post('exp_job_level') . "<br />";
            $detail.= "<b>Área:</b> " . $this->input->post('exp_area');

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Experiencia Laboral Agregada',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}
        
        echo "done";
    }
    
    public function edit()
    {	
        $seeker_exp = $this->Jobseeker_experience->find($this->input->post('ed_exp_id'));
        $seeker = $this->Job_seeker->find($seeker_exp->seeker_ID);

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
        
        try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Experiencia laboral: </b> " . $this->input->post('ed_job_title') . "<br />";
            $detail.= "<b>Empresa:</b> " . $this->input->post('ed_company_name') . "<br />";
            $detail.= "<b>Puesto:</b> " . $this->input->post('ed_exp_job_level') . "<br />";
            $detail.= "<b>Área:</b> " . $this->input->post('ed_exp_area');

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Experiencia Laboral Editada',
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

        $seeker_exp = $this->Jobseeker_experience->find($this->input->post('id'));
        $seeker = $this->Job_seeker->find($seeker_exp->seeker_ID);

        $this->Jobseeker_experience->delete($this->input->post('id'));

        try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $detail = "<b>Experiencia laboral: </b> " . $seeker_exp->job_title . "<br />";
            $detail.= "<b>Empresa:</b> " . $seeker_exp->company_name . "<br />";
            $detail.= "<b>Puesto:</b> " . $seeker_exp->job_level . "<br />";
            $detail.= "<b>Área:</b> " . $seeker_exp->area;

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Experiencia Laboral Eliminada',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

        echo "done";
    }
    
    public function experience_by_id()
    {	
        $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
        
        $row = $this->Jobseeker_experience->get_record_by_id($this->input->post('id'));
        echo json_encode($row);
    }

    public function upload_certificate()
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

        $experience_id = $this->input->post('experience_id');

        $file_previus = $this->Jobseeker_experience->find(
            $experience_id
        );

        $seeker_id = $file_previus->seeker_ID;

        $seeker = $this->Job_seeker->find($seeker_id);

        try {
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/experience_certificates/' . md5(uniqid($experience_id . $seeker_id, true)) . $file_ext;
            
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

        $trans_status = $this->Jobseeker_experience->save_certificate(
            $experience_id, 
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

            $detail = "<b>Experiencia laboral: </b> " . $file_previus->job_title . "<br />";
            $detail.= "<b>Empresa:</b> " . $file_previus->company_name . "<br />";
            $detail.= "<b>Puesto:</b> " . $file_previus->job_level . "<br />";
            $detail.= "<b>Área:</b> " . $file_previus->area . "<br />";
            $detail.= "<b>Certificado: </b> <a href='" . $file_url . "'>Ver</a>";
            
			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Experiencia Laboral - Carga Certificado',
				'detalle' => $detail,
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }
}
