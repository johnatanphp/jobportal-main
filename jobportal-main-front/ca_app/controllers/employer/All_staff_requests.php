<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_staff_requests extends CI_Controller 
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Employer_staff_request_manage_business_unit');
        //Load helper
        $this->load->helper('form');
    }   

    public function index()
    {
        $this->search();
    }

    public function search()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Solicitudes recibidas - ' . SITE_NAME;

        //Obtener usuario en sesión
        $user_id = $this->session->userdata('user_id');

        $business_unit_codes = $this->Employer_staff_request_manage_business_unit
                                    ->get_business_units_by_user_id($user_id);

        //Obtener filtros
        $filters = $this->Staff_request->validate_filter_search_staff_request([
            'query' => $this->input->get('query'),
            'status_rq' => $this->input->get('status_rq', true),
            'status_rs' => $this->input->get('status_rs', true),
            'type' => $this->input->get('type', true),
            'recruiter' => $this->input->get('recruiter', true),
            'employer' => $this->input->get('employer', true),
            'business_unit_codes' => $business_unit_codes,
            'request_year' => $this->input->get('request_year', true)
        ]);
       
        $obj_employer = $this->Employer->get_employer_by_id($user_id);

        $total_rows = $this->count_all_staff_requests_by_company_id(
            $obj_employer->company_ID, 
            $filters
        );
        
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

        $result_requests = $this->search_all_staff_requests_by_company_id(
            $obj_employer->company_ID, 
            $filters,
            $per_page, 
            $page
        );

        $data['links'] = $this->pagination->create_links();
        $data['filters'] = $filters;
        $data['result_requests'] = $result_requests;
        $data['result_employers'] = $this->Employer->get_all_by_profile_id($obj_employer->company_ID, 1);
        $data['result_recruiters'] = $this->Employer->get_all_by_profile_id($obj_employer->company_ID, 2);
        $data['total_requests'] = $total_rows;

        $this->load->view('employer/staff_request/staff_request_all', $data);
    }

    public function export_excel()
    {
        //Obtener usuario en sesión
        $user_id = $this->session->userdata('user_id');
        
        $business_unit_codes = $this->Employer_staff_request_manage_business_unit
                                    ->get_business_units_by_user_id($user_id);

        $obj_employer = $this->Employer->get_employer_by_id($user_id);

        //Obtener filtros
        $filters = [
            'query' => $this->input->get('query', true),
            'status_rq' => $this->input->get('status_rq', true),
            'status_rs' => $this->input->get('status_rs', true),
            'type' => $this->input->get('type', true),
            'recruiter' => $this->input->get('recruiter', true),
            'employer' => $this->input->get('employer', true),
            'business_unit_codes' => $business_unit_codes,
            'company_id' => $obj_employer->company_ID,
            'request_year' => $this->input->get('request_year', true),
        ];

        $this->load->library('Exports/Staff_request_all_export', null, 'Staff_request_all_export');

        $this->Staff_request_all_export->build($filters)->download('Todas-solicitudes.xlsx');
    }

    public function search_all_staff_requests_by_company_id(
        $company_id, 
        $filter = [], 
        $per_page = 0, 
        $page = 0
    ) {   

        $user =  $this->Employer->find($this->session->userdata('user_id'));

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
            'request.employer_ID',
            'request.request_model_id',
            //Get data staff recruiter
            'app_user_recruiters.ID AS recruiter_ID',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'app_user_recruiters.email AS recruiter_email',
            //Posted job
            'post_job.ID AS job_ID',
            'recruitment_process.sts_stage AS rs_process_status',
            'IFNULL(assigned_employers.employer_ID, assigned_employers.employer_ID) AS request_assigned_employer_id'
        ]);
        
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');
        
        $this->db->where('request.company_ID', $company_id);
        
        if ($filter['request_year'] != 'all') {
            $this->db->where('request.creation_date>=', $filter['request_year'] . '-01-01 00:00:00');
            $this->db->where('request.creation_date<=', $filter['request_year'] . '-12-31 23:59:59');
        }

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

        if (!empty($filter['employer']) && $filter['employer'] != 'all') {
            $this->db->where('assigned_employers.employer_ID', $filter['employer']);
        }

        if ($user->is_admin != 'yes') {
            $this->db->where_in('request.cod_business_unit', $filter['business_unit_codes']);
        }

        $this->db->order_by('FIELD(request.sts_process, 
            "unassigned", 
            "assigned",
            "published", 
            "pending", 
            "suspended", 
            "rejected", 
            "canceled")'
        );

        $this->db->order_by('request.ID', 'desc');
        $this->db->order_by('recruitment_process.sts_stage', 'desc');
        $this->db->group_by('request.ID');

        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

    public function count_all_staff_requests_by_company_id($company_id, $filter = [])
    {
        $user =  $this->Employer->find($this->session->userdata('user_id'));

        $this->db->select('request.ID');
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');
      
        $this->db->where('request.company_ID', $company_id);

        if ($filter['request_year'] != 'all') {
            $this->db->where('request.creation_date>=', $filter['request_year'] . '-01-01 00:00:00');
            $this->db->where('request.creation_date<=', $filter['request_year'] . '-12-31 23:59:59');
        }

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

        if (!empty($filter['employer']) && $filter['employer'] != 'all') {
            $this->db->where('assigned_employers.employer_ID', $filter['employer']);
        }

        if ($user->is_admin != 'yes') {
            $this->db->where_in('request.cod_business_unit', $filter['business_unit_codes']);
        }
        
        $this->db->group_by('request.ID');

        return $this->db->count_all_results();
    }
}
