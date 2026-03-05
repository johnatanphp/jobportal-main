<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Companies extends CI_Controller 
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$company_name = $this->uri->segment(2);

		if ($company_name == '') {
			redirect(base_url(),'');
			exit;
		}
		
		$row_company = $this->Company->get_company_by_slug($company_name);
		
		if (!$row_company || $row_company->sts != 'active') {
			show_404();	
		}
		
		$total_opened_jobs = $this->Posted_job->count_opened_posted_jobs_by_company_id($row_company->ID);

		$config = pagination_configuration(pagination_url(), $total_opened_jobs, 15, 3, 5, true, true, true);
        
        $this->pagination->initialize($config);
        $page = (int)$this->input->get('page');
        $page_num = $page - 1;
        $page_num = ($page_num < 0) ? 0 : $page_num;
        $page = $page_num * $config["per_page"];
        $data["links"] = $this->pagination->create_links();

        $result_posted_jobs = $this->Posted_job->search_opened_posted_jobs_by_company_id(
			$row_company->ID, 
			[],
			$config["per_page"], 
            $page
		);

		$job_url = $row_company->company_slug . '-jobs-in-';
		
		$company_website = ($row_company->company_website!='')?validate_company_url($row_company->company_website):'';
		$data['page']               = $page;
		$data['row_company'] 		= $row_company;
		$data['total_opened_jobs'] 	= $total_opened_jobs;
		$data['job_url']		 	= $job_url;
		$data['result_posted_jobs'] = $result_posted_jobs;
		$data['company_website'] 	= $company_website;
		$data['title'] 				= $row_company->company_name;
		$this->load->view('company_view', $data);
	}
	
	public function is_already_applied_for_job($user_id, $job_id){
		$is_already_applied = '';
		if($this->session->userdata('is_job_seeker')==TRUE){
			$is_already_applied = $this->Applied_jobs->count_applied_job_by_seeker_and_job_id($user_id, $job_id);
			$is_already_applied = ($is_already_applied>0)?'yes':'no';
		}	
		return $is_already_applied;
	}
}
