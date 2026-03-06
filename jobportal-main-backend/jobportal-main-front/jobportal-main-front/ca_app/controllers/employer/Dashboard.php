<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME . ': Dashboard del empleador';
		
		$row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		//Posted jobs
		$result_posted_jobs = $this->Posted_job->get_posted_job_by_employer($row->ID, 'all', 'all', 5, 0);
		//Total opened jobs
		$total_opened_jobs = $this->Posted_job->count_all_posted_jobs_by_company_id_frontend($row->company_ID);
		
		//Applied Jobs by Employer ID
		$result_applied_jobs = $this->Applied_jobs->get_applied_job_by_employer_id($row->ID, 5, 0);
		
		$company_logo = ($row->company_logo)?$row->company_logo:'no_logo.jpg';
		
		$job_url = $row->company_slug.'-jobs-in-';
		
		$data['row'] 				= $row;
		$data['total_opened_jobs'] 	= $total_opened_jobs;
		$data['job_url']		 	= $job_url;
		$data['result_posted_jobs'] = $result_posted_jobs;
		$data['result_applied_jobs']= $result_applied_jobs;
		$data['company_logo'] 		= $company_logo;
		$this->load->view('employer/employer_dashboard_view',$data);
	}
}
