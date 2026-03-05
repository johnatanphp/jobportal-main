<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Request_results extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Exam_request_result');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Recruitment_attached_document');
        $this->load->model('Exam_request_result_type');
        $this->load->model('Exam_request_seeker');

        //Load libraries
        $this->load->library('storage_lib');
    }
    
    public function index()
    {
        $this->search();
    }

    public function search($loaded = 0)
    {
        $data['title'] = 'Carga de resultados';
        $data['ads_row'] = $this->ads;

        $user = $this->Employer->find($this->session->userdata('user_id'));

        $filters = [
            'query' => $this->input->get('query', true),
            'loaded' => $loaded ? 1 : 0,
            'company_id' => $user->company_ID
        ];

        $total_rows = $this->Exam_request_result->count_candidates(
            $filters
        );
    
        $config = pagination_configuration(pagination_url(), $total_rows, 15, 3, 5, true, true, true);

        $this->pagination->initialize($config);
        
        $page = (int)$this->input->get('page');
        $page_num = $page - 1;
        $per_page = $config['per_page'];
        $page_num = ($page_num < 0) ? '0' : $page_num;
        $page = $page_num * $config["per_page"];

        $result_candidates = $this->Exam_request_result->search_candidates(
            $filters,
            $per_page,
            $page
        );

        $data['links'] = $this->pagination->create_links();
        $data['total_candidates'] = $total_rows;
        $data['result_candidates'] = $result_candidates; 
        $data['filters'] = $filters;

        $this->load->view('employer/exam_requests/exam_requests/list_seeker_results', $data); 
    }

    public function detail(
        $job_id = 0, 
        $seeker_id = 0
    )
    {
        $candidate = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

        $job = $this->Posted_job->get_posted_job_by_id($job_id);
    
        $rs_process_candidate = $this->Recruitment_candidate->get_candidate_process(
            $job_id, 
            $seeker_id
        );

        $data['title'] = 'Carga de resultados';
        $data['ads_row'] = $this->ads;

        $data['job'] = $job;
        $data['candidate'] = $candidate;
        $data['rs_process_candidate'] = $rs_process_candidate;

        $this->load->view('employer/exam_requests/exam_requests/exam_result_detail', $data);
    }

    public function get_exam_results($job_id, $seeker_id)
    {
        $exam_documents = $this->Exam_request_result->get_exam_results_by_seeker($job_id, $seeker_id);

        $data['job_id'] = $job_id;
        $data['seeker_id'] = $seeker_id;
        $data['documents'] = $exam_documents;

        $this->load->view('employer/exam_requests/exam_requests/common/exam_result_list_documents', $data);
    }

    public function modal_upload()
    {
        $result_id = $this->input->post('result_id');

        $exam_result = $this->Exam_request_result->find($result_id);

        $data['result_options'] = $this->Exam_request_result_type->get_options_by_exam_type($exam_result);

        $data['result_id'] = $result_id;
        
        $this->load->view('employer/exam_requests/exam_requests/modal/exam_result_upload', $data);
    }

    public function upload()
    {   
        $result_id = $this->input->post('result_id');

        $exam_result = $this->Exam_request_result->find($result_id);

        if (!$exam_result) {
            exit(json_encode([
                'success' => false,
                'error' => '¡Error al cargar el documento!'
            ]));
        }

        if ($exam_result->loaded == 1) {
            exit(json_encode([
                'success' => false,
                'error' => '¡Documento ya está cargado!'
            ]));
        }

        $file = isset($_FILES['doc_file']) ? $_FILES['doc_file'] : null;

        if (is_null($file)) {
            exit(json_encode([
                'success' => false,
                'error' => '¡Documento a cargar no encontrado!'
            ]));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode([
                'success' => false,
                'error' => '¡Tipo de archivo subido no es válido!'
            ]));
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode([
                'success' => false,
                'error' => '¡El archivo a subir debe ser menor o igual a 4MB!'
            ]));
        }
  
        try {
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'employer/recruitment_selection_documents/' . md5(uniqid('exam-request-result-' . $result_id, true)) . $file_ext;
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                'success' => false,
                'error' => '¡No se pudo subir el archivo!'
            ]));
        }

        $this->storage_lib->setVisibility($path, 'public');

        $exam_request_seeker = $this->Exam_request_seeker->find($exam_result->exam_request_seeker_id);

        $this->db->trans_start();   

        $file_name = $this->Exam_request_result->get_name_file(
            $exam_result->exam_type_id, 
            $exam_result->type
        );
        
        $data_doc_file = [
           'name' => $file_name,
           'file_source' => $path,
           'created_at' => date('Y-m-d H:i:s'),
           'loaded_by' => $this->session->userdata('user_id'),
           'key' => $exam_result->document_type,
           'job_ID' => $exam_request_seeker->job_id,
           'seeker_ID' => $exam_request_seeker->seeker_id,
           'exam_request_result_id' => $result_id,
           'loaded_by_area' => 'sso'
        ];

        $this->db->insert('tbl_recruitment_attached_documents', $data_doc_file);
        $doc_file_id = $this->db->insert_id();

        $data_result = [
            'result_status' => $this->input->post('approved'),
            'result_file_id' => $doc_file_id,
            'loaded' => 1
        ];

        $this->db->where('id', $result_id);
        $this->db->update('tbl_exam_request_results', $data_result);

        $this->db->trans_complete();

        echo json_encode(['success' => $this->db->trans_status()]);
    }

    public function delete()
    {
        $result_id = $this->input->post('id');

        $exam_result = $this->Exam_request_result->find($result_id);

        $doc_file = $this->db->get_where('tbl_recruitment_attached_documents', [
            'ID' => $exam_result->result_file_id
        ])->row();

        if (!$exam_result || !$doc_file) {
            exit(json_encode([
                'success' => false
            ]));
        }

        $this->db->trans_start();

        $this->db->where('ID', $exam_result->result_file_id);
        $this->db->delete('tbl_recruitment_attached_documents'); 

        try {
            $this->storage_lib->delete($doc_file->file_source);
        } catch (Exception $e) {}
        
        $this->db->where('id', $exam_result->id);
        $this->db->update('tbl_exam_request_results', [
            'loaded' => 0,
            'result_file_id' => null,
            'result_status' => null
        ]);

        $this->db->trans_complete();

        echo json_encode([
            'success' => $this->db->trans_status()
        ]);
    }
}
