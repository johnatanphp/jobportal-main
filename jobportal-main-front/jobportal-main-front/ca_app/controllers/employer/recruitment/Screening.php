<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }

    public function create_form()
    {
        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $data['screening_types'] = $this->get_screening_resource_value($job_id);
        $data['job_id'] = $job_id;
        $data['seeker_id'] = $seeker_id;
        
        $this->load->view('employer/recruitment/screening/common/screening_create_form', $data);
    }

    public function create()
    {
        $this->load->library(
            'Screening/Screening_jobseeker_search_lib', 
            null, 
            'Screening_jobseeker_search_lib'
        );

        $seeker_id = $this->input->post('seeker_id');
        $jobseeker = $this->Job_seeker->find($seeker_id);
        $screening_type = $this->input->post('type');

        if (!$jobseeker || empty($jobseeker->document_number)) {
            echo json_encode([
                'status' => false,
                'message' => 'Postulante no encontrado, no tiene documento de identidad correcto'
            ]);
            return;
        }

        $document_number = $jobseeker->document_number;

        $job_id = $this->input->post('job_id');
        $job = $this->Posted_job->find($job_id);

        if (!$job) {
            echo json_encode([
                'status' => false,
                'message' => 'Empleo no es correcto'
            ]);
            return;
        }

        if (!$job->request_ID) {
            echo json_encode([
                'status' => false,
                'message' => 'El proceso no tiene una solicitud asociada'
            ]);
            return;
        }

        $staff_request = $this->Staff_request->find($job->request_ID);

        if (!$job->request_ID) {
            echo json_encode([
                'status' => false,
                'message' => 'La solicitud del proceso no es correcta'
            ]);
            return;
        }

        if (empty($screening_type)) {
            echo json_encode([
                'status' => false,
                'message' => 'El tipo de screening es inválido'
            ]);
            return;
        }
        
        $resource_value_array = $this->get_screening_resource_value($job_id);

        if (!in_array($screening_type, array_keys($resource_value_array))) {
            echo json_encode([
                'status' => false,
                'message' => 'Tipo de screening es incorrecto'
            ]);
            return;
        }

        $response = $this->Screening_jobseeker_search_lib->create([
            'job_id' => $job_id,
            'document_number' => $document_number,
            'type' => $screening_type,
            'seeker_id' => $seeker_id,
            'cost_center' => $staff_request->cost_center
        ]);
    
        echo json_encode([
            'status' => $response['status'],
            'message' =>  $response['message'],
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_files()
    {
        $this->load->model('Seeker_screening');

        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $seeker = $this->Job_seeker->find($seeker_id);

        $data['screening_results'] = $this->Seeker_screening->get_all_results([
            'document_number' => $seeker->document_number
        ]);

        $this->load->view('employer/recruitment/screening/common/screening_results', $data);
    }

    public function upload()
    {
        $this->load->model('Recruitment_document_type');
        $this->load->library('Storage_lib');

        $this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
        $this->form_validation->set_rules('seeker_id', 'Candidato', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Hay datos incorrectos'
            ]);
            return;
        }

        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $document_key = 'screnning';
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

    private function get_screening_resource_value($job_id)
    {
        $this->load->model('Exam_request_seeker');

        $job = $this->Posted_job->find($job_id);

        if (!$job) {
            return false;
        }

        if (!$job->request_ID) {
            return false;
        }

        $staff_request = $this->Staff_request->find($job->request_ID);

        if (!$job->request_ID) {
            return false;
        }

        $screening_data = $this->Exam_request_seeker->build_data_for_screening($staff_request);

        $resource_value = isset($screening_data['resource_screening']) ? ($screening_data['resource_screening'])->resource_value : '';  

        $types = explode(',', (string)$resource_value);
        $screening_results = [];

        $this->db->from('tbl_screening_types');
        $this->db->where_in('name', $types);
        $this->db->where('active', 1);
        $result_types = $this->db->get()->result();

        foreach ($result_types as $type) {
            $screening_results[$type->id] = $type->name;
        }

        return $screening_results;
    }
}
