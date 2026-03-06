<?php

class Recruitment_candidate_process_detail_loader 
{   
    public function __construct(){}

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function render($params = [])
    {
        $process_id = $params['process_id'] ?? 0;
        $candidate_id = $params['candidate_id'] ?? 0;
        
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_attached_document');
		$this->load->model('Recruitment_document_type');
		
		$this->load->library('Url_signer/Url_signer_lib');
		
		$process = $this->Recruitment_process->find($process_id);
		
		if (!$process) {
		    show_404();
		}
		
		$job_id = $process->job_ID;
		
        $process_company = $this->Recruitment_process->get_company_by_process_id($process_id);
   
       	$row = $this->Job_seeker->get_job_seeker_by_id($candidate_id);
       	
       	if (!$row) {
       		show_404();
       	}
   
       	$job = $this->Posted_job->get_posted_job_by_id($job_id);
       
       	if (!$job) {
       		show_404();
       	}
        
       	$data = jobseeker_cv_data($candidate_id);
        $data['config'] = $params['config'] ?? [];
       	$data['result_answers_applicant'] = [];
       	$data['job_id'] = $job_id;
       	$data['job'] = $job;
       	
       	$data['candidate_id'] = $candidate_id;
       	
       	$data['rs_document_counter'] = $this->Recruitment_attached_document->get_counter_group_by_document_key(
       	    $job_id,
       		$candidate_id
       	);
       
       	$data['recruitment_process'] = $this->Recruitment_process->get_process_by_id($process_id);
       
       	$data['candidate_process'] = $this->Recruitment_candidate->get_candidate_by_process_id($process_id, $candidate_id);
       
       	$data['process_company'] = $process_company;
    
       	$data['rys_documents'] = $this->Recruitment_document_type->all(['active' => 1, 'country_id' => $process_company->country_id]);
       
        $row_applied = $this->Applied_jobs->get_applied_job_by_seeker_and_job_id($candidate_id, $job_id);
         
       	if (isset($row_applied->ID)) {
       		$data['result_answers_applicant'] = $this->Applied_jobs->get_answers_applicant(
       			$row_applied->ID
       		);
       		$data['applied_id'] = $row_applied->ID;
       	}
       
       	$this->load->view('jobseeker/modal/show_rs_detail_candidate_view', $data);
    }
}
