<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Batch extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
        
        //Load models 
        $this->load->model('Screening_batch');
        $this->load->model('Screening_batch_item');
        $this->load->model('Workflow_cost_center');
    }	

    public function index() 
    {   
        $data['ads_row'] = $this->ads;
        $data['title'] = "Screening por lotes";

        $this->db->select([
            'batch.id',
            'batch.created_at',
            'batch.description',
            'batch.status_id',
            "CASE
                WHEN batch.status_id = 1 THEN 'PROCESANDO'
                WHEN batch.status_id = 2 THEN 'PROCESADO'
                ELSE '-' 
             END AS status_name",
            "CASE
             WHEN batch.status_id = 1 THEN 'label-warning'
             WHEN batch.status_id = 2 THEN 'label-success'
             ELSE 'label-default' 
            END AS status_label",

        ]);
        $this->db->from('tbl_screening_batch batch');
        $this->db->where('created_by', $this->session->userdata('user_id'));
        $batch_results = $this->db->get()->result();

        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $cost_centers = $this->Workflow_cost_center->get_all($employer->company_ID);

        $data['cost_centers'] = $cost_centers;
        $data['batch_results'] = $batch_results;

        $this->load->view('employer/screening/batch/batch_list', $data);
    }

    public function show($batch_id = 0)
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = "Detalle del Lote";
        
        $data['batch'] = $this->Screening_batch->find(['id' => $batch_id]);

        $now = date('Y-m-d 00:00:00');

        $this->db->select([
            'batch_items.id AS batch_item_id',
            'batch_items.document_number AS batch_item_document_number', 
            'screening_types.name AS batch_items_type_name',
            'screening.id AS screening_id',
            'screening.created_at AS screening_created_at',
            'screening.response_code AS screening_response_code',
            'screening.its_data_prosecution AS screening_its_data_prosecution',
            'DATE_ADD(screening.created_at, INTERVAL 6 MONTH) AS screening_due_date',
            'DATEDIFF(DATE_ADD(screening.created_at, INTERVAL 6 MONTH), "' . $now . '") AS screening_remaining_days' 
        ]);
        $this->db->from('tbl_screening_batch batch');
        $this->db->join('tbl_screening_batch_items batch_items', 'batch_items.batch_id=batch.id');
        $this->db->join('tbl_screening_types screening_types', 'screening_types.id=batch_items.type_id');
        $this->db->join('tbl_screening screening', 'screening.id=batch_items.screening_id', 'left');
        $this->db->where('batch.id', $batch_id);
        
        $data['batch_items'] = $this->db->get()->result();

        $this->load->view('employer/screening/batch/batch_show', $data);
    }

    public function register_file_validate()
    {
        $this->load->library('Imports/Screening_batch_import');
        
        $response = $this->screening_batch_import->validate();

        echo json_encode([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => [
                'total_rows' => $response['data']['total_rows'] ?? 0
            ]
        ]);
    }

    public function create()
    {
        $this->form_validation->set_rules('batch_description', 'Descripción lote', 'trim|required');        
        $this->form_validation->set_rules('type', 'Tipo', 'trim|required|in_list_db[tbl_screening_types.id]');        
        $this->form_validation->set_rules('job_title', 'Puesto', 'trim|required|max_length[128]|strip_all_tags');
		$this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('type_expense', 'Tipo egreso', 'trim|required|max_length[20]|strip_all_tags');
        $this->form_validation->set_rules('eecc_code', 'Estructura de costo', 'trim|max_length[20]|strip_all_tags');
        $this->form_validation->set_rules('cost_center_client', 'Centro de costo cliente', 'trim|max_length[50]|strip_all_tags');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            echo json_encode([
				'status' => false,
				'message' => current($message_error)
			]);
			return;
        }

        $this->load->library('Imports/Screening_batch_import');
        
        //dd($this->input->post());
        $response = $this->screening_batch_import->save($this->input->post());

        if ($response['status'] == false) {
            echo json_encode([
                'status' => $response['status'],
                'message' => $response['message']
            ]);
            return;
        }
        
        echo json_encode([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => [
                'batch_detail_url' => site_url('employer/screening/batch/show/' . $response['data']['id'])
            ]
        ]);
    }

    public function detail_error()
    {
        $params = $this->input->get();

        $this->db->select([
            'screening.response'
        ]);
        $this->db->from('tbl_screening_batch_items batch_items');
        $this->db->join('tbl_screening screening', 'screening.id=batch_items.screening_id');
        $this->db->where('batch_items.id', $params['id']);
        $this->db->where('screening.response_code', 0);
        
        $batch_item = $this->db->get()->row();

        $data['batch_item'] = $batch_item;
        $this->load->view('employer/screening/batch/common/content_screening_show_error', $data);
    }
}
