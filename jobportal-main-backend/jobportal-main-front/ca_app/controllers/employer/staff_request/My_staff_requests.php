<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class My_staff_requests extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$this->search();
	}

	public function search()
	{
		$this->load->helper('form');

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Mis solicitudes - ' . SITE_NAME;

		//Obtener filtros
		$filters = $this->Staff_request->validate_filter_search_staff_request([
			'query' => $this->input->get('query', true),
			'status_rq' => $this->input->get('status_rq', true),
			'status_rs' => $this->input->get('status_rs', true),
			'type' => $this->input->get('type', true),
			'employer' => $this->input->get('employer', true)	
		]);

		$user_id = $this->session->userdata('user_id');
		
		//Obtener usuario en sesión
		$obj_recruiter = $this->Employer->find($user_id);
		
		//Pagination starts
		$total_rows = $this->count_all($obj_recruiter->ID, $filters);
		
		$config = pagination_configuration(
            pagination_url(), 
            $total_rows, 
            $this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
            3, 
            1, 
            true, 
            true,
            true
        );
			
		$this->pagination->initialize($config);
   		
        $page = (int)$this->input->get('page');
		$page_num = $page - 1;
		$per_page = $config['per_page'];
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $config["per_page"];

		$result_requests = $this->search_all(
			$obj_recruiter->ID, 
			$filters, 
			$per_page, 
			$page
		);
		
		$data['links'] = $this->pagination->create_links();
		$data['filters'] = $filters;
		$data['result_employers'] = $this->Employer->get_employers_by_company_id($obj_recruiter->company_ID);
		$data['result_requests'] = $result_requests;
		$data['total_requests'] = $total_rows;

		$this->load->view('employer/staff_request/my_staff_requests_view', $data);
	}

	public function export_excel()
	{
		$user_id = $this->session->userdata('user_id');

		//Obtener usuario en sesión
		$obj_recruiter = $this->Employer->find($user_id);

		//Obtener filtros
		$filters = [
			'query' => $this->input->get('query', true),
			'status_rq' => $this->input->get('status_rq', true),
			'status_rs' => $this->input->get('status_rs', true),
			'type' => $this->input->get('type', true),
			'employer' => $this->input->get('employer', true),
			'recruiter_id' => $user_id
		];

		$this->load->library('Exports/Staff_request_my_requests_export', null, 'Staff_request_my_requests_export');

        $this->Staff_request_my_requests_export->build($filters)->download('Mis-solicitudes.xlsx');
	}

	public function search_all(
        $recruiter_id, 
        $filter = [], 
        $per_page = 0, 
        $page = 0
    ) {
        $this->db->select([
            //Datos solicitud
            'request.ID',
            'request.creation_date',
            'request.consultant_name',
            'request.business_unit_name',
            'request.client_company_name',
            'request.cost_center',
            'request.job_title',
            'request.request_type',
            'request.sts_process',
            'request.request_model_id',
            //Datos solicitante
            'app_user_recruiters.ID AS recruiter_ID',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'app_user_recruiters.email AS recruiter_email',
            //Datos R&S
            'recruitment_process.sts_stage AS rs_process_status',
            'recruitment_process.job_ID AS rs_job_ID',
            'recruitment_process.id AS process_id'
        ]);
    	
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
	    $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
		$this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');

        $this->db->where('request.recruiter_ID', $recruiter_id);
   
        if ($filter['status_rq'] != 'all') {
            $this->db->where('request.sts_process', $filter['status_rq']);
        }

        if ($filter['status_rs'] != 'all') {
            $this->db->where('recruitment_process.sts_stage', $filter['status_rs']);
        }
 
        if ($filter['type'] != 'all') {
            $this->db->where('request.request_type', $filter['type']);
        }

        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('request.ID', $filter['query']);
            $this->db->or_like('request.job_title', $filter['query']);
            $this->db->group_end();
        }

        if (!empty($filter['employer']) && $filter['employer'] != 'all') {
            $this->db->where('assigned_employers.employer_ID', $filter['employer']);
        }

    	$this->db->order_by('request.ID', 'DESC');
		$this->db->group_by('request.ID');

        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

    	return $this->db->get()->result();
    }

    public function count_all($recruiter_id, $filter = [])
    {
		$this->db->select('request.ID');
    	$this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
		$this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');

        $this->db->where('request.recruiter_ID', $recruiter_id);

        if ($filter['status_rq'] != 'all') {
            $this->db->where('request.sts_process', $filter['status_rq']);
        }

        if ($filter['status_rs'] != 'all') {
            $this->db->where('recruitment_process.sts_stage', $filter['status_rs']);
        }

        if ($filter['type'] != 'all') {
            $this->db->where('request.request_type', $filter['type']);
        }

        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('request.ID', $filter['query']);
            $this->db->or_like('request.job_title', $filter['query']);
            $this->db->group_end();
        }

        if (!empty($filter['employer']) && $filter['employer'] != 'all') {
            $this->db->where('request.employer_ID', $filter['employer']);
        }

		$this->db->group_by('request.ID');
        
    	return $this->db->count_all_results();
    }
}
