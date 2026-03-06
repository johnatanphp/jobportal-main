<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requested_documents extends CI_Controller
{	
	public function __construct()
    {
        parent::__construct();

		//load model
		$this->load->model('Requested_document');
		$this->load->model('Jobseeker_required_document');
        $this->load->model('Jobseeker_form_rtps');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Rys_form_seeker');

        $this->load->model('Seeker_domicile_affidavit');
        $this->load->model('Seeker_declaration_5th_category');
        $this->load->model('Seeker_immigration_record');
        $this->load->model('Seeker_residency_verification');
        $this->load->model('Seeker_permission_signs_contract');

        $this->load->model('Recruitment_process_contract_document');
        $this->load->model('Recruitment_contract_document');

		//Load libraries
		$this->load->library('upload');
        
        $this->ads = $this->Ad->get_ads();
    }

	public function index()
	{
        $job_seeker_id = $this->session->userdata('user_id');
        $data['title'] = "Documentos solicitados - Ingreso Overall - " . SITE_NAME;
        $data['seeker'] = $this->Job_seeker->find($job_seeker_id);
        $data['ads_row'] = $this->ads;

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($job_seeker_id);

        if (!candidate_is_process_contracting()) {
            $this->load->view('jobseeker/requested_documents/list_requested_documents_error_view', $data);
            return;
        }
        
        $data['rs_process'] = $rs_candidate;

		$data['identification_document'] = $this->Requested_document->get_identification_document(
			$job_seeker_id
		);

        $data['domicile_affidavit'] = $this->Seeker_domicile_affidavit->get_latest_for_seeker(
            $job_seeker_id
        );

        $data['certificate_5th_category'] = $this->Jobseeker_required_document->get_certificate_5th_category(
            $job_seeker_id
        );

        $data['studies'] = $this->Job_seeker->get_qualification_by_jobseeker_id(
            $job_seeker_id
        );

        $data['experiences'] = $this->Job_seeker->get_experience_by_jobseeker_id(
            $job_seeker_id
        );

        $data['count_experience_certificates'] = $this->Job_seeker->count_experience_with_certificate(
            $job_seeker_id
        );

        $data['count_study_certificates'] = $this->Job_seeker->count_study_with_certificate(
            $job_seeker_id
        );

        $data['form_rtps'] = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_ID' => $job_seeker_id,
            'job_id' => $rs_candidate->job_ID  
        ])->row();

        $data['entry_form'] = $this->db->get_where('tbl_entry_form', [
            'seeker_ID' => $job_seeker_id,
            'process_id' => $rs_candidate->process_id  
        ])->row();

        $data['form_affidavit'] = $this->Rys_form_seeker->get_assignment_by(
            1,
            $rs_candidate->job_ID, 
            $job_seeker_id
        );      

        $data['immigration_records'] = $this->Seeker_immigration_record->find(['seeker_id' => $job_seeker_id]);
        
        $data['residency_verifications'] = $this->Seeker_residency_verification->find(['seeker_id' => $job_seeker_id]);

        $data['documents'] = $this->Recruitment_process_contract_document->get_documents($rs_candidate->job_ID);

		$this->load->view('jobseeker/requested_documents/list_requested_documents_view', $data);
	}

	public function send_identification_document()
	{
		$job_seeker_id = $this->session->userdata('user_id');
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Enviar documento de identidad - ' . SITE_NAME;
        
        $data['seeker'] = $this->Job_seeker->find($job_seeker_id);
		
        $documents = [1]; //DNI
        if (($data['seeker']->document_type != '1')) {
            $documents = [4, 7, 12, 13];
        }
        
        $data['documents'] = $this->Requested_document->get_identity_documents_by_type($job_seeker_id, $documents);

        $data['sign_contract'] = $this->Seeker_permission_signs_contract->find(['seeker_id' => $job_seeker_id]);

		$this->load->view('jobseeker/requested_documents/send_identification_document', $data);
	}

	public function upload_identification_document($doc_type = 1)
	{
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode([
                'success' => false,
                'message' => 'Documento no cargado'
            ]));
        }

        if ($file['error'] > 0) {
            exit(json_encode([
                'success' => false,
                'message' => 'Documento no cargado - error ' .  $file['error']
            ]));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'application/pdf',
            'image/png'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode([
                'success' => false,
                'message' => 'Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode([
                'success' => false,
                'message' => 'El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        $seeker_id = $this->session->userdata('user_id');

        // $file_previus = $this->Requested_document->get_identification_document(
        //     $seeker_id, 
        //     $doc_type
        // );

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid($doc_type . '-' . $seeker_id, true)) . $file_ext;  

            $path = 'candidate/identification_documents/' . $file_name;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                'success' => false,
                'message' => 'No se pudo subir la imagen'
            ]));
        } 

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Requested_document->save_identification_document(
            $seeker_id,
            $doc_type,
            $path
        );

        if (!$trans_status) {
            exit(json_encode([
                'success' => false,
                'message' => 'Error al guardar la imagen'
            ]));
        }

        // if ($file_previus && 
        //     $this->storage_lib->has($file_previus->path)) {
        //     $this->storage_lib->delete($file_previus->path);
        // }
     
        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'message' => 'Documento cargado',
            'url_file' => $file_url
        ]);
	}

    public function delete_identification_document() {
        header('Content-Type: application/json');

        // Verificar si el usuario está autenticado
        $seeker_id = $this->session->userdata('user_id');
        if (!$seeker_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
            return;
        }

        // Obtener doc_type del POST
        $doc_type = $this->input->post('doc_type');

        // Obtener documento previo
        $file_previus = $this->Requested_document->get_identification_document($seeker_id, $doc_type);

        if (!$file_previus) {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró el documento para eliminar'
            ]);
            return;
        }

        try {
            $this->load->library('storage_lib');

            // Eliminar archivo del almacenamiento
            if ($this->storage_lib->has($file_previus->path)) {
                $this->storage_lib->delete($file_previus->path);
            }

            // Eliminar registro de la base de datos
            $trans_status = $this->Requested_document->delete_identification_document($seeker_id, $doc_type);

            if (!$trans_status) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el registro de la base de datos'
                ]);
                return;
            }

        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo eliminar el archivo'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Documento eliminado'
        ]);
    }

	public function send_domicile_affidavit()
	{
		$seeker_id = $this->session->userdata('user_id');
		$data['ads_row'] = $this->ads;
        $data['seeker_id'] = $seeker_id;
		$data['title'] = 'Declaración Jurada Domicilio - ' . SITE_NAME;

        $rys_process = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($seeker_id);

        if (!$rys_process) {
            show_404();
        }

        $job = $this->Posted_job->get_posted_job_by_id($rys_process->job_ID);

        if (!$rys_process) {
            show_404();
        }

        $data['job'] = $job;
        $data['seeker'] = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
        //$data['record'] = $this->Seeker_domicile_affidavit->get_for_job_id($job->ID, $seeker_id);
        $data['record'] = $this->Seeker_domicile_affidavit->get_latest_for_seeker($seeker_id);

		$this->load->view('jobseeker/requested_documents/send_domicile_affidavit', $data);
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

    public function upload_study_certificates()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
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

        $seeker_id = $this->session->userdata('user_id');
        $study_id = $this->input->post('study_id');

        $file_previus = $this->Jobseeker_academic->find(
            $study_id
        );
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/study_certificates/' .  md5(uniqid($study_id . $seeker_id, true)) . $file_ext;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

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
            $this->storage_lib->has($file_previus->attached_certificate)) {
            $this->storage_lib->delete($file_previus->attached_certificate);
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

    public function send_experience_certificate()
    {
        $data['title'] = 'Certificados de trabajo - ' . SITE_NAME;
        $data['ads_row'] = $this->ads;

        $job_seeker_id = $this->session->userdata('user_id');
        $result_studies = $this->Job_seeker->get_experience_by_jobseeker_id($job_seeker_id);        
        $data['result_experiences'] = $result_studies;        

        $this->load->view('jobseeker/requested_documents/send_experience_certificate', $data);
    }

    public function upload_experience_certificate()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
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

        $seeker_id = $this->session->userdata('user_id');
        $experience_id = $this->input->post('experience_id');

        $file_previus = $this->Jobseeker_experience->find(
            $experience_id
        );

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/experience_certificates/' . md5(uniqid($experience_id . $seeker_id, true)) . $file_ext;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

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
            $this->storage_lib->has($file_previus->attached_certificate)) {
            $this->storage_lib->delete($file_previus->attached_certificate);
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

    public function send_declaration_5th_category()
    {
        $seeker_id = $this->session->userdata('user_id');
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Declaración de 5ta categoría - ' . SITE_NAME;

        $rys_process = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($seeker_id);

        if (!$rys_process) {
            show_404();
        }

        $job = $this->Posted_job->get_posted_job_by_id($rys_process->job_ID);

        if (!$rys_process) {
            show_404();
        }

        $data['job'] = $job;
        $data['seeker'] = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
        $data['record'] = $this->Seeker_declaration_5th_category->get_for_job_id($job->ID, $seeker_id);
        $data['latest'] = $this->Seeker_declaration_5th_category->get_latest_for_seeker($seeker_id);

        $this->load->view('jobseeker/requested_documents/send_declaration_5th_category', $data);
    }

    public function modal_show_document_comments()
    {
        $seeker_id = $this->session->userdata('user_id');
        $document_key = $this->input->post('document');
        $ref_id = $this->input->post('ref_id') ? $this->input->post('ref_id') : null;
        
        $rs_document = $this->Jobseeker_required_document->get_recruitment_document(
            $seeker_id,
            $document_key,
            $ref_id
        );

        $data['rs_document'] = $rs_document;

        $this->load->view('jobseeker/modal/show_rs_document_comments', $data);
    }

    public function view_cert($key)
    {   
        $seeker_id = $this->session->userdata('user_id');

        if ($key == 1) {
            $this->load->library(
                'Pdf/Domicile_affidavit_cert_pdf', 
                null, 
                'Domicile_affidavit_cert_pdf'
            );

            $this->Domicile_affidavit_cert_pdf->show($seeker_id);
        } else if ($key == 2) {
            $this->load->library(
                'Pdf/Declaration_5th_category_pdf', 
                null, 
                'Declaration_5th_category_pdf'
            );

            $this->Declaration_5th_category_pdf->show($seeker_id);
        } else {
            show_404();
        }
    }

    public function domicile_affidavit_send_evicertia($job_id)
    {
        $seeker_id = $this->session->userdata('user_id');

        $this->load->library(
            'Evicertia_domicile_affidavit_lib', 
            null, 
            'Evicertia_domicile_affidavit_lib'
        );

        $status = $this->Evicertia_domicile_affidavit_lib->send([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id
        ]);

        echo json_encode([
            'success' => $status
        ]);
    }

    public function declaration_5th_category_send_evicertia($job_id)
    {
        $seeker_id = $this->session->userdata('user_id');

        $this->load->library(
            'Evicertia_declaration_5th_category_lib', 
            null, 
            'Evicertia_declaration_5th_category_lib'
        );

        $status = $this->Evicertia_declaration_5th_category_lib->send(
            $job_id,
            $seeker_id
        );

        echo json_encode([
            'success' => $status
        ]);
    }

    public function send_immigration_records()
    {
        $job_seeker_id = $this->session->userdata('user_id');
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Record migratorio vigente - ' . SITE_NAME;

        $data['file'] = $this->Seeker_immigration_record->find(['seeker_id' => $job_seeker_id]);

        $this->load->view('jobseeker/requested_documents/send_immigration_records', $data);
    }

    public function upload_immigration_records()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
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

        $seeker_id = $this->session->userdata('user_id');

        $file_previus = $this->Seeker_immigration_record->find(['seeker_id' => $seeker_id]);
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/immigration_records/' . md5(uniqid($seeker_id, true)) . $file_ext;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Seeker_immigration_record->create_or_update(
            ['seeker_id' => $seeker_id], 
            ['seeker_id' => $seeker_id, 'file_path' => $path]
        );

        if (!$trans_status) {
            exit(json_encode(
                ['error' => 'Error al guardar el documento']
            ));
        }

        if ($file_previus && 
            $file_previus->file_path &&
            $this->storage_lib->has($file_previus->file_path)) {
            $this->storage_lib->delete($file_previus->file_path);
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

    public function send_residency_verifications()
    {
        $job_seeker_id = $this->session->userdata('user_id');
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Verificación de residencia vigente - ' . SITE_NAME;

        $data['file'] = $this->Seeker_residency_verification->find(['seeker_id' => $job_seeker_id]);

        $this->load->view('jobseeker/requested_documents/send_residency_verifications', $data);
    }

    public function upload_residency_verifications()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
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

        $seeker_id = $this->session->userdata('user_id');

        $file_previus = $this->Seeker_residency_verification->find(['seeker_id' => $seeker_id]);
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/residency_verificactions/' . md5(uniqid($seeker_id, true)) . $file_ext;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Seeker_residency_verification->create_or_update(
            ['seeker_id' => $seeker_id], 
            ['seeker_id' => $seeker_id, 'file_path' => $path]
        );

        if (!$trans_status) {
            exit(json_encode(
                ['error' => 'Error al guardar el documento']
            ));
        }

        if ($file_previus && 
            $file_previus->file_path &&
            $this->storage_lib->has($file_previus->file_path)) {
            $this->storage_lib->delete($file_previus->file_path);
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

    public function upload_permission_signs_contracts()
	{
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode([
                'success' => false,
                'message' => 'Documento no cargado']
            ));
        }

        if ($file['error'] > 0) {
            exit(json_encode([
                'success' => false,
                'message' => 'Documento no cargado - error ' .  $file['error']
            ]));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode([
                'success' => false,
                'message' => 'Tipo de archivo no es válido'
            ]));
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode([
                'success' => false,
                'message' => 'El archivo a subir debe ser menor o igual a 4MB'
            ]));
        }

        $seeker_id = $this->session->userdata('user_id');

        $file_previus = $this->Seeker_permission_signs_contract->find(['seeker_id' => $seeker_id]);

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid($seeker_id, true)) . $file_ext;  

            $path = 'candidate/permission_signs_contracts/' . $file_name;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                'success' => false,
                'message' => 'No se pudo subir la imagen'
            ]));
        }

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Seeker_permission_signs_contract->create_or_update(
            ['seeker_id' => $seeker_id], 
            ['seeker_id' => $seeker_id, 'file_path' => $path]
        );

        if (!$trans_status) {
            exit(json_encode([
                'success' => false,
                'message' => 'Error al guardar la imagen'
            ]));
        }

        if ($file_previus && 
            $this->storage_lib->has($file_previus->file_path)) {
            $this->storage_lib->delete($file_previus->file_path);
        }
     
        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'message' => 'Documento cargado',
            'url_file' => $file_url
        ]);
	}
}
