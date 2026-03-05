<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scheduled_exams extends CI_Controller 
{    
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
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Programación de exámenes - ' . SITE_NAME;

        //Get employer in sesion
        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
        
        $rs_status = $this->input->get('rs_status');
        $rs_stage = $this->input->get('rs_stage');
        $employer_query = $this->input->get('employer_query');
        $employer_id = $this->input->get('employer_id');

        if ($obj_employer->is_admin == 'yes') {
            $options_employer = array('self', 'select', 'all');
        } else {
            $options_employer = array('self');
        }

        //Validate options filters
        if (!in_array($employer_query, $options_employer)) {
            $employer_query = $obj_employer->is_admin == 'yes' ? 'all' : 'self';
        }

        if (!in_array($rs_stage, array(0, 1, 2, 3, 4, 5, 6, 7, 'all'))) {
            $rs_stage = 'all';
        }

        if (!in_array($rs_status, array('active', 'finished', 'canceled', 'suspended', 'not_started', 'all'))) {
            $rs_status = 'all';
        }

        if ($employer_query == 'self') {
            $employer_id = $obj_employer->ID;
        }

        $filters = array(
            'rs_status' => $rs_status,
            'rs_stage' => $rs_stage,
            'employer_query' => $employer_query,
            'employer_id' => $employer_id,
            'query' => $this->input->get('query')
        );
        
        //Pagination starts
        $total_rows = $this->count_all(
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
        $page_num = $page-1;
        $page_num = ($page_num<0) ? '0' : $page_num;
        $page = $page_num*$config["per_page"];
        $data["links"] = $this->pagination->create_links();
        //Pagination ends

        //Jobs by company
        $result_posted_jobs = $this->search_all(
            $obj_employer->company_ID, 
            $filters, 
            $config["per_page"], 
            $page
        );
                
        $company_logo = ($obj_employer->company_logo) ? $obj_employer->company_logo : 'no_logo.jpg';
        
        $job_url = $obj_employer->company_slug . '-jobs-in-';

        $data['row'] = $obj_employer;
        $data['job_url'] = $job_url;
        $data['result_posted_jobs'] = $result_posted_jobs;
        $data['total_jobs'] = $total_rows;
        $data['company_logo'] = $company_logo;
        $data['employers'] = $this->Employer->get_employers_by_company_id($obj_employer->company_ID);
        $data['filters'] = $filters;
        $data['result_rs_stages'] = get_RS_stages();
    
        $this->load->view('employer/recruitment_scheduled_exams/search_view', $data);
    }

    private function search_all(
        $company_id, 
        $filters, 
        $per_page, 
        $page
    ) 
    {
        $employer_query = isset($filters['employer_query']) ? $filters['employer_query'] : 'all';
        $employer_id = isset($filters['employer_id']) ? $filters['employer_id'] : null;
        $rs_status = isset($filters['rs_status']) ? $filters['rs_status'] : 'all';
        $rs_stage = isset($filters['rs_stage']) ? $filters['rs_stage'] : 'all'; 
        $pj_status = isset($filters['status']) ? $filters['status'] : 'all';
        $query = isset($filters['query']) ? $filters['query'] : '';
        $expired = isset($filters['expired']) ? $filters['expired'] : 'all';

        $this->db->select([
            'pj.ID', 
            'pj.job_title', 
            'pj.job_slug', 
            'pj.job_description', 
            'pj.employer_ID', 
            'pj.last_date', 
            'pj.dated', 
            'pj.city', 
            'pj.is_featured', 
            'pj.sts', 
            'pj.applications_count', 
            'pj.viewer_count',
            'pj.contact_person',
            'pj.request_ID AS request_ID',
            'pc.company_name', 
            'pc.company_logo', 
            'app_user_employers.email AS employer_email', 
            'app_user_employers.first_name AS employer_name',
            'recruitment_process.sts_stage AS recruiment_sts_stage',
            'recruitment_process.sts AS recruiment_sts', 
        ]);
       
        $this->db->from('tbl_post_jobs pj'); 
        $this->db->join('tbl_companies pc', 'pj.company_ID=pc.ID');
        $this->db->join('tbl_employers app_user_employers', 'pj.employer_ID=app_user_employers.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=pj.ID', 'left');
        $this->db->join('tbl_staff_requests sr', 'sr.ID=pj.request_ID', 'left');
        $this->db->join('tbl_staff_request_exam_request_employers exam_request_employers', 'exam_request_employers.request_ID=sr.ID', 'left');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=sr.ID', 'left');
       
        $this->db->where('exam_request_employers.employer_id', $this->session->userdata('user_id'));
        $this->db->where('pj.company_ID', $company_id);
  
        if ($employer_query != 'all') {
            $this->db->or_where('assigned_employers.employer_ID', $employer_id);
        }

        if ($rs_stage != 'all') {
            $this->db->where('recruitment_process.sts_stage', $rs_stage);
        }
    
        if ($rs_status != 'all' && $rs_status != 'not_started') {
            $this->db->where('recruitment_process.sts', $rs_status);
        }

        //R&S NO INICIADO
        if ($rs_status == 'not_started') {
            $this->db->where('recruitment_process.sts', null);
        }

        if ($pj_status != 'all') {
            $this->db->where('pj.sts', $pj_status);
        }

       if ($expired == 'yes') {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        } elseif ($expired == 'no') {
            $this->db->where('pj.last_date>', date('Y-m-d'));
        }

        if ($query != '') {
            if (is_numeric($query)) {
                $this->db->where('pj.ID', $query);
            }

            if (!is_numeric($query)) {
                $this->db->like('pj.job_title', $query);
            }
        }

        $this->db->order_by('pj.dated', 'DESC');
        $this->db->order_by('FIELD(recruitment_process.sts, "finished", "canceled", "active", "suspended") DESC');
        
        $this->db->group_by('pj.ID');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all($company_id, $filters = []) 
    {    
        $employer_query = isset($filters['employer_query']) ? $filters['employer_query'] : 'all';
        $employer_id = isset($filters['employer_id']) ? $filters['employer_id'] : null;
        $rs_status = isset($filters['rs_status']) ? $filters['rs_status'] : 'all';
        $rs_stage = isset($filters['rs_stage']) ? $filters['rs_stage'] : 'all';
        $query = isset($filters['query']) ? $filters['query'] : '';
        $pj_status = isset($filters['status']) ? $filters['status'] : 'all';
        $expired = isset($filters['expired']) ? $filters['expired'] : 'all';

        $this->db->select('pj.ID');
        $this->db->from('tbl_post_jobs pj'); 
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=pj.ID', 'left');
        $this->db->join('tbl_staff_requests sr', 'sr.ID=pj.request_ID', 'left');
        $this->db->join('tbl_staff_request_exam_request_employers exam_request_employers', 'exam_request_employers.request_ID=sr.ID', 'left');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=sr.ID', 'left');

        $this->db->where('exam_request_employers.employer_id', $this->session->userdata('user_id'));
        $this->db->where('pj.company_ID', $company_id);

        if ($employer_query != 'all') {
            $this->db->where('assigned_employers.employer_ID', $employer_id);
        }

        if ($rs_stage != 'all') {
            $this->db->where('recruitment_process.sts_stage', $rs_stage);
        }
        
        if ($rs_status != 'all' && $rs_status != 'not_started') {
            $this->db->where('recruitment_process.sts', $rs_status);
        }

        //R&S NO INICIADO
        if ($rs_status == 'not_started') {
           $this->db->where('recruitment_process.sts', null);
        }

        if ($pj_status != 'all') {
            $this->db->where('pj.sts', $pj_status);
        }
        
        if ($expired == 'yes') {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        } elseif ($expired == 'no') {
            $this->db->where('pj.last_date>', date('Y-m-d'));
        }

        if ($query != '') {
            if (is_numeric($query)) {
                $this->db->where('pj.ID', $query);
            }

            if (!is_numeric($query)) {
                $this->db->like('pj.job_title', $query);
            }
        }

        $this->db->group_by('pj.ID');

        return $this->db->count_all_results();
    }   
}
