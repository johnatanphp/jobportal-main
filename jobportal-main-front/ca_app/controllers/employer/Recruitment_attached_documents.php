<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_attached_documents extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        $this->load->model('Recruitment_attached_document');
        $this->load->model('Recruitment_document_type');
        $this->load->model('Exam_request_result_type');

        //Load libraries
        $this->load->library('storage_lib');
    }
    
    public function modal_documents(
        $job_id = 0, 
        $candidate_id = 0, 
        $document_key = null
    )
    {
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

        $document = $this->Recruitment_document_type->find_by_key($document_key);

        $data['job_id'] = $job_id;
        $data['candidate_id'] = $candidate_id;
        $data['document'] = $document;

        $data['attached_files'] = $this->Recruitment_attached_document->get_files(
            $job_id, 
            $candidate_id,
            $document_key
        );

        $this->load->view('employer/recruitment/modal/recruitment_documents', $data);
    }

    public function modal_other_documents(
        $job_id = 0, 
        $candidate_id = 0, 
        $stage = 0
    )
    {
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

        $data['job_id'] = $job_id;
        $data['candidate_id'] = $candidate_id;
        $data['stage'] = $stage;

        $data['attached_files'] = $this->Recruitment_attached_document->get_other_files(
            $job_id, 
            $candidate_id,
            $stage
        );
    
        $this->load->view('employer/recruitment/modal/rs_seeker_other_documents', $data);
    }

    public function upload_document()
    {
        $this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
        $this->form_validation->set_rules('candidate_id', 'Candidato', 'trim|required');
        $this->form_validation->set_rules('document', 'Documento', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'error' => 'Hay datos incorrectos'
            ]);
            return;
        }

        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('candidate_id');
        $document_key = $this->input->post('document');
        $loaded_by = $this->session->userdata('user_id');

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => '¡Debe cargar un documento válido!']
            ));
        }

        $document_row = $this->Recruitment_document_type->find_by_key($document_key);

        if (!$document_row) {
            exit(json_encode(
                ['error' => '¡Documento key es invalido!']
            ));
        }

        $mime_types_allowed = get_mime_types(explode(',', $document_row->allowed_files));

        if (!in_array($file['type'], $mime_types_allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > ($document_row->max_size * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }
       
        try {
        
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid('rys-documents-' . $job_id . '-' . $document_key . '-' . $seeker_id, true)) . $file_ext;  
            
            $path = 'employer/recruitment_selection_documents/' . $file_name;

            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'error' => 'Error al cargar el documento - No se pudo subir el documento'
                ])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $data = [
           'name' => $file['name'],
           'file_source' => $path,
           'created_at' => date('Y-m-d H:i:s'),
           'key' => $document_key,
           'job_ID' => $job_id,
           'seeker_ID' => $seeker_id,
           'loaded_by' => $loaded_by
        ];

        $this->db->insert('tbl_recruitment_attached_documents', $data); 
        $file_id = $this->db->insert_id();

        if (!$file_id) {
            exit(json_encode([
                    'error' => 'Error al guardar el documento'
                ])
            );
        }
     
        $file_url = file_url($path);

        echo json_encode([
            'original_file_name' => $file['name'],
            'url_file' => $file_url,
            'file_id' => $file_id
        ]);
    }

    public function remove()
    {   
        $file_id = $this->input->post('file_id');

        $document = $this->Recruitment_attached_document->get_by_id($file_id);

        if (!$document) {
            show_404();
        }

        $job = $this->Posted_job->get_posted_job_by_id($document->job_ID);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        $trans_status = $this->Recruitment_attached_document->remove(
            $file_id
        );

        echo json_encode(array(
            'success' => $trans_status
        ));
    }

    public function upload_other_document()
    {
        $this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
        $this->form_validation->set_rules('candidate_id', 'Candidato', 'trim|required');
        $this->form_validation->set_rules('document_title', 'Documento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('stage', 'Etapa', 'trim|required|strip_all_tags');

        if ($this->form_validation->run() === FALSE) {
            
            echo json_encode([
                'error' => 'Hay datos incorrectos'
            ]);
            return;
        }

        $job_id = $this->input->post('job_id'); 
        $seeker_id = $this->input->post('candidate_id'); 
        $stage = $this->input->post('stage'); 
        $document_title = trim($this->input->post('document_title', true));

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
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
       
        try {
        
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid('rys-other-documents-' . $job_id . '-' . $stage . '-' . $seeker_id, true)) . $file_ext;  
            
            $path = 'employer/recruitment_selection_documents/' . $file_name;

            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'error' => 'Error al cargar el documento - No se pudo subir el documento'
                ])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $data = [
           'name' => $file['name'],
           'document_title' => $document_title,
           'stage' => $stage,
           'file_source' => $path,
           'created_at' => date('Y-m-d H:i:s'),
           'key' => 'other_documents',
           'job_ID' => $job_id,
           'seeker_ID' => $seeker_id
        ];

        $this->db->insert('tbl_recruitment_attached_documents', $data); 
        
        $file_id = $this->db->insert_id();

        $file_url = file_url($path);

        echo json_encode([
            'original_file_name' => $file['name'],
            'document_title' => $document_title,
            'url_file' => $file_url,
            'file_id' => $file_id
        ]);
    }

    public function remove_other_file()
    {/*
        $file_id = $this->input->post('file_id');
        
        $trans_status = $this->Recruitment_attached_document->remove(
            $file_id
        );

        echo json_encode(array(
            'success' => $trans_status
        ));

        */

        $this->remove();
    }
}
