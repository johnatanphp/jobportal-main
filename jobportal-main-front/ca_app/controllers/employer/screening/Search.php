<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        $this->load->model('Job_seeker');
        $this->load->model('Seeker_screening');
    }	

    public function index() 
    {   
        $this->load->model('Workflow_cost_center');

        $data['ads_row'] = $this->ads;
        $data['title'] = "Consulta Screening";
        $data['results'] = [];

        $results = [];
        $seeker = null;        
        $document_number = trim($this->input->get('document_number') ?? '');

        $employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if (!empty($document_number)) {

            check_permission_action('screening', 'search');

            $this->db->from('tbl_job_seekers');
            $this->db->where('document_number', $document_number);
            $seeker = $this->db->get()->row();
            $results = $this->Seeker_screening->get_all_results([
                'document_number' => $document_number
            ]);
        }

        $cost_centers = $this->Workflow_cost_center->get_all($employer->company_ID);

        $data['document_number'] = $document_number;
        $data['results'] = $results;
        $data['seeker'] = $seeker;
        $data['cost_centers'] = $cost_centers;

        $this->load->view('employer/screening/search', $data);
    }

    public function do_screening()
    {
        check_permission_action('screening', 'create');

        $this->load->library(
            'Screening/Screening_jobseeker_search_lib', 
            null, 
            'Screening_jobseeker_search_lib'
        );

        $this->form_validation->set_rules('document_number', 'N° de documento', 'trim|required');
        $this->form_validation->set_rules('type', 'Tipo', 'trim|required|in_list_db[tbl_screening_types.id]');        
        $this->form_validation->set_rules('job_title', 'Puesto', 'trim|required|max_length[128]|strip_all_tags');
		$this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('type_expense', 'Tipo egreso', 'trim|required|max_length[20]|strip_all_tags');
        $this->form_validation->set_rules('eecc_code', 'Estructura de costo', 'trim|max_length[20]|strip_all_tags');
        $this->form_validation->set_rules('cost_center_client', 'Centro de costo cliente', 'trim|max_length[50]|strip_all_tags');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors()
			]);
			return;
		}

        $document_number = $this->input->post('document_number');
        $type = $this->input->post('type');
        $cost_center = $this->input->post('cost_center');

        $this->db->from('tbl_job_seekers');
        $this->db->where('document_number', $document_number);
        $jobseeker = $this->db->get()->row();
        
        $response = $this->Screening_jobseeker_search_lib->create([
            'document_number' => $document_number,
            'type' => $type,
            'seeker_id' => $jobseeker ? $jobseeker->ID : null,
            'cost_center' => $cost_center,
            'job_title' => $this->input->post('job_title'),
            'type_expense' => $this->input->post('type_expense'),
            'eecc_code' => $this->input->post('eecc_code'),
            'cost_center_client' => $this->input->post('cost_center_client')
        ]);
    
        if ($response['status'] == false) {
            echo json_encode([
                'status' => $response['status'],
                'message' => $response['message'],
                'data' => []
            ]);

            return;
        }

        $this->session->set_flashdata('success',  $response['message']);

        echo json_encode([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => []
        ]);
    }

    public function get_screening()
    {
        check_permission_action('screening', 'search');
        
        $id = $this->input->get('id');

        $this->db->from('tbl_screening');
        $this->db->where('id', $id);
        $screening = $this->db->get()->row();

        $this->db->from('tbl_screening_types');
        $this->db->where('id', $screening->type_id);
        $screening_type = $this->db->get()->row();

        $result = json_decode($screening->response);

        $data['screening'] = $screening;
        $data['screening_type'] = $screening_type;
        
        $data['result_data'] = @$result->data;
        
        $this->load->view('employer/screening/common/screening_show', $data);
    }

    public function export()
    {
        check_permission_action('screening', 'report_list');

        $params = $this->input->get();

        $this->form_validation->set_data($params);
        $this->form_validation->set_rules('start_date', 'Fecha de inicio', 'trim|required|valid_date');
        $this->form_validation->set_rules('end_date', 'Fecha fin', 'trim|required|valid_date|date_greater_than_equal_to[' . $params['start_date'] . ']');        

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors()
			]);
			return;
		}

        $start_date = $params['start_date'];
        $end_date = $params['end_date'];

        $start_date_time = new DateTime($start_date);
        $end_date_time = new DateTime($end_date);
        $diff = $start_date_time->diff($end_date_time);

        if ($diff->days > 62) {
            echo json_encode([
				'status' => false,
				'message' => 'El rango seleccionado no debe superar los 2 meses'
			]);
			return;
        }
       
        $this->load->library(
            'Exports/Operational_reports/Screening/Screening_list_export', 
            null, 
            'Screening_list_export'
        );

		$report_name = "Listado-screening-" . $start_date_time->format('Y-m-d') . "-al-" . $end_date_time->format('Y-m-d') . ".xlsx";

		$user = $this->Employer->find($this->session->userdata('user_id'));
		$report_class = $this->Screening_list_export;

        $report_class->build([
			'start_date' => $start_date_time->format('Y-m-d'),
			'end_date' => $end_date_time->format('Y-m-d'),
			'user_id' => $user->ID,
			'company_id' => $user->company_ID
		]);
		$report_class->download($report_name);
    }
}
