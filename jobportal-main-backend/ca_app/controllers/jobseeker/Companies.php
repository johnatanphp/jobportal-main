<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Companies extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
    	
    	//force record the data
		//validate_jobseeker_data();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME.': Empresas';
		
		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($this->session->userdata('user_id'));
		
		//Pagination starts
		$total_rows = $this->Applied_jobs->count_applied_job_jobseeker_id($this->session->userdata('user_id'));
		$config = pagination_configuration(base_url("jobseeker/companies"), $total_rows, 50, 3, 5, true);
		
		$this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(3) : 0;
		$page_num = $page-1;
		$page_num = ($page_num<0)?'0':$page_num;
		$page = $page_num*$config["per_page"];
		$data["links"] = $this->pagination->create_links();
		//Pagination ends
		
		//Applied Jobs by Employer ID
		$result_applied_jobs = $this->Applied_jobs->get_applied_jobs_by_jobseeker_id($this->session->userdata('user_id'), $config["per_page"], $page);
		$data['result_applied_jobs']= $result_applied_jobs;
		$this->load->view('jobseeker/companies_view',$data);
	}
	
}
