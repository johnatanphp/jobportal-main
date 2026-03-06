<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_assigned_staff_requests extends CI_Controller {
	
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
		$data['title'] = 'Mis solicitudes asignadas - ' . SITE_NAME;

		//Obtener filtros
		$filters = $this->Staff_request->validate_filter_search_staff_request(
			array(
				'query' => $this->input->get('query'),
				'status_rq' => $this->input->get('status_rq', true),
				'status_rs' => $this->input->get('status_rs', true),
				'type' => $this->input->get('type', true),
				'recruiter' => $this->input->get('recruiter', true)
			)
		);

		//Get user in sesion
		$obj_employer = $this->Employer->find($this->session->userdata('user_id'));

		//Pagination starts
		$total_rows = $this->count_all($filters);
		
		$config = pagination_configuration(
			pagination_url(), 
			$total_rows, 
			$this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
			3, 
			5, 
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

		$result_requests = $this->search_all($filters, $per_page, $page);

		$data['links'] = $this->pagination->create_links();
		$data['filters'] = $filters;
		$data['result_recruiters'] = $this->Employer->get_all_by_profile_id($obj_employer->company_ID, 2);
		$data['result_requests'] = $result_requests;
		$data['total_requests'] = $total_rows;

		$this->load->view('employer/staff_request/staff_request_assigned', $data);
	}

	public function export_excel()
	{
		$this->load->library('report_excel_lib');

		//Obtener filtros
		$filters = $this->Staff_request->validate_filter_search_staff_request(
			array(
				'query' => $this->input->get('query', true),
				'status_rq' => $this->input->get('status_rq', true),
				'status_rs' => $this->input->get('status_rs', true),
				'type' => $this->input->get('type', true),
				'recruiter' => $this->input->get('recruiter', true),
			)
		);

		$result_requests = $this->search_all(
			$filters
		);

		$rows = array();

		$row[] = 'Consultora';
		$row[] = 'Unidad de negocio';
		$row[] = 'Empresa cliente';
		$row[] = 'Centro de costo';
		$row[] = 'Puesto';
		$row[] = 'Fecha solicitud';
		$row[] = 'Usuario solicitante';
		
		$rows[] = $row;

		foreach ($result_requests as $request) {
			$row = array();
			$row[] = $request->consultant_name;
			$row[] = $request->business_unit_name;
			$row[] = $request->client_company_name;
			$row[] = $request->cost_center;
			$row[] = $request->job_title;
			$row[] = $request->creation_date;
			$row[] = $request->recruiter_first_name;

			$rows[] = $row;
		}

		$this->report_excel_lib->build($rows);
		$this->report_excel_lib->download("Todas-las-solicitudes-asignadas");
	}

	public function search_all(
        $filter = [],
        $per_page = 0, 
        $page = 0
    ) {   

		$employer_id = $this->session->userdata('user_id');

        $this->db->select([
            //Get data staff request
            'request.ID',
            'request.consultant_name',
            'request.business_unit_name',
            'request.client_company_name',
            'request.cost_center',
            'request.creation_date',
            'request.job_title',
            'request.request_type',
            'request.sts_process',
			'request.request_model_id',
            //Get data staff recruiter
            'app_user_recruiters.ID AS recruiter_ID',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'app_user_recruiters.email AS recruiter_email',
            //request assigned to employer 
            'app_user_employers.ID AS employer_ID',
            'app_user_employers.first_name AS employer_first_name',
            'app_user_employers.email AS employer_email',
            'recruitment_process.sts_stage AS rs_process_status'
        ]);
        
        $this->db->from('tbl_staff_requests request');
		$this->db->join('tbl_staff_request_assigned_employers assigned_employer', 'request.ID=assigned_employer.request_ID');
        $this->db->join('tbl_employers app_user_employers', 'app_user_employers.ID=assigned_employer.employer_ID');
		$this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');

        $this->db->where('assigned_employer.employer_ID', $employer_id);

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

        if (!empty($filter['recruiter']) && $filter['recruiter'] != 'all') {
            $this->db->where('request.recruiter_ID', $filter['recruiter']);
        }
 
        $this->db->order_by('FIELD(request.sts_process, "assigned","published","canceled")');
        $this->db->order_by('request.ID', 'desc');

        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

	private function count_all($filter = [])
	{
		$employer_id = $this->session->userdata('user_id');

        $this->db->select('request.ID');
        $this->db->from('tbl_staff_requests request');
		$this->db->join('tbl_staff_request_assigned_employers assigned_employer', 'request.ID=assigned_employer.request_ID');
        $this->db->join('tbl_employers app_user_employers', 'app_user_employers.ID=assigned_employer.employer_ID');
		$this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');

        $this->db->where('assigned_employer.employer_ID', $employer_id);

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

        if (!empty($filter['recruiter']) && $filter['recruiter'] != 'all') {
            $this->db->where('request.recruiter_ID', $filter['recruiter']);
        }

		return $this->db->count_all_results();
	}
}
