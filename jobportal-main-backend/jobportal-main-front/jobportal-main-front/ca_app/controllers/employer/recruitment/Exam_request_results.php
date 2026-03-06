<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_request_results extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }

    public function content_exam_request_results()
    {        
        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $document_type = $this->input->post('document');

        $this->load->view('employer/recruitment/exam_request_results/common/exam_request_documents');
    }

    public function get_files()
    {
        $this->load->library(
            'Exam_request/Exam_request_get_all_lib', 
            null, 
            'Exam_request_get_all_lib'
        );

        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $document_type = $this->input->post('document');
    
        $data['exam_request_results'] = $this->Exam_request_get_all_lib->results(
            $job_id,
            $seeker_id, 
            $document_type
        );
        
        $this->load->view('employer/recruitment/exam_request_results/common/exam_request_results', $data);
    }

    public function upload()
    {
        $this->load->model('Recruitment_document_type');
        $this->load->library('Storage_lib');

        $this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
        $this->form_validation->set_rules('seeker_id', 'Candidato', 'trim|required');
        $this->form_validation->set_rules('document', 'Documento', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Hay datos incorrectos'
            ]);
            return;
        }

        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $document_key = $this->input->post('document');
        $loaded_by = $this->session->userdata('user_id');

        $job = $this->Posted_job->find($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            echo json_encode([
                'status' => false,
                'message' => 'Debe cargar un documento válido'
            ]);
            return;
        }

        $mime_types_allowed = get_mime_types([
            '.jpg',
            '.png',
            '.docx',
            '.pdf'
        ]);

        if (!in_array($file['type'], $mime_types_allowed)) {
            echo json_encode([
                'status' => false,
                'message' => 'Tipo de archivo no es válido'
            ]);
            return;
        }

        if ($file['size'] > (4 * 1048576)) {
            echo json_encode([
                'status' => false,
                'message' => 'El archivo a subir debe ser menor o igual a 4MB'
            ]);
            return;
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
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo subir el documento'
            ]);
            return;
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
            echo json_encode([
                'status' => false,
                'message' => 'Error al guardar el documento'
            ]);
            return;
        }
     
        $file_url = file_url($path);

        echo json_encode([
            'status' => true,
            'message' => 'Documento ha sido guardado',
            'data' => [
                'file_url' => $file_url
            ]
        ]);
    }

    public function upload_remove()
    {   
        $this->load->model('Recruitment_attached_document');

        $file_id = $this->input->post('file_id');

        $document = $this->Recruitment_attached_document->get_by_id($file_id);

        if (!$document) {
            echo json_encode([
                'status' => false,
                'message' => 'Archivo a eliminar no encontrado',
                'data' => []
            ]);
        }

        $job = $this->Posted_job->find($document->job_ID);

        if (!$job) {
            echo json_encode([
                'status' => $trans_status,
                'message' => 'Empleo Id es inválido',
                'data' => []
            ]);
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            echo json_encode([
                'status' => $trans_status,
                'message' => 'No tiene permiso para eliminar este archivo',
                'data' => []
            ]);
        }

        $trans_status = $this->Recruitment_attached_document->remove(
            $file_id
        );

        echo json_encode([
            'status' => $trans_status,
            'message' => 'Archivo eliminado',
            'data' => []
        ]);
    }
}
