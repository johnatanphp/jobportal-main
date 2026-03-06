<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_Posted_Jobs extends CI_Controller {
	
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
		$data['title'] = 'Administrar empleos - ' . SITE_NAME;
		
		//Get employer in sesion
		$obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		
		$status = $this->input->get('status');
		$expired = $this->input->get('expired');
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

		if (!in_array($status, array('active', 'inactive', 'blocked', 'pending', 'all'))) {
			$status = 'all';
		}

		if (!in_array($expired, array('yes', 'no', 'all'))) {
			$expired = 'all';
		}
		
		if ($employer_query == 'self') {
			$employer_id = $obj_employer->ID;
		}

		$filters = array(
			'expired' => $expired,
			'status' => $status,
			'employer_query' => $employer_query,
			'employer_id' => $employer_id,
			'query' => $this->input->get('query')
		);
		
		//Pagination starts
		$total_rows = $this->Posted_job->count_all_posted_jobs_by_company_id($obj_employer->company_ID, $filters);
	
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
		$result_posted_jobs = $this->Posted_job->search_posted_jobs_by_company_id($obj_employer->company_ID, $filters, $config["per_page"], $page);
				
		$company_logo = ($obj_employer->company_logo) ? $obj_employer->company_logo : 'no_logo.jpg';
		
		$job_url = $obj_employer->company_slug . '-jobs-in-';

		$data['row'] = $obj_employer;
		$data['job_url'] = $job_url;
		$data['result_posted_jobs'] = $result_posted_jobs;
		$data['total_jobs'] = $total_rows;
		$data['company_logo'] = $company_logo;
		$data['employers'] = $this->Employer->get_employers_by_company_id($obj_employer->company_ID);
		$data['filters'] = $filters;
	
		$this->load->view('employer/my_posted_jobs_view', $data);
	}

	public function export()
	{
		$obj_employer = $this->Employer->find($this->session->userdata('user_id'));

		$params = $this->input->get();

		$employer_id = $obj_employer->ID;

		if ($obj_employer->is_admin == 'yes') {
			$employer_id = $params['employer_id'];
		}
		 
		$filters = [
			'company_id' => $obj_employer->company_ID,
			'expired' => 0,//$params['expired'] ?? 0,
			'status' => $params['status'] ?? '21',
			'employer_id' => $employer_id
		];

		$this->load->library('Exports/Posted_job_list_export', null , 'Posted_job_list_export');

		$this->Posted_job_list_export->build($filters);

        $this->Posted_job_list_export->download('Listado-empleos.xlsx');
	}
}
