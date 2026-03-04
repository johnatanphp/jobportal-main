<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_Applications extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME.': Lista de solicitudes recibidas';
		
		//Pagination starts
		$total_rows = $this->Applied_jobs->count_applied_job_by_employer_id($this->session->userdata('user_id'));
		$config = pagination_configuration(base_url("employer/job_applications"), $total_rows, 50, 3, 5, true);
		
		$this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(3) : 0;
		$page_num = $page-1;
		$page_num = ($page_num<0)?'0':$page_num;
		$page = $page_num*$config["per_page"];
		$data["links"] = $this->pagination->create_links();
		//Pagination ends
		
		//Applied Jobs by Employer ID
		$result_applied_jobs = $this->Applied_jobs->get_applied_job_by_employer_id($this->session->userdata('user_id'), $config["per_page"], $page);
		$data['result_applied_jobs']= $result_applied_jobs;
		$this->load->view('employer/job_applications_view',$data);
	}
	
	public function send_message_to_candidate(){
		if(!$this->session->userdata('user_id')){
			echo 'All fields are mandatory.';
			exit;	
		}
		$this->form_validation->set_rules('message', 'Mensaje', 'trim|required|strip_all_tags|time_diff');
		$this->form_validation->set_rules('jsid', 'ID', 'trim|required|strip_all_tags');
		$this->form_validation->set_error_delimiters('', '');
		if ($this->form_validation->run() === FALSE) {
			echo validation_errors();
			exit;
		}
		
		if($this->session->userdata('is_employer')!=TRUE){
			echo 'You are not logged in with a employer account. Please login with a employer account to send message to the candidate.';
			exit;
		}
		
		$decrypted_id = $this->custom_encryption->decrypt_data($this->input->post('jsid'));
		
		$row_jobseeker 	= $this->Job_seeker->get_job_seeker_by_id($decrypted_id);
		$row_employer 	= $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		if(!$row_jobseeker){
			echo 'Something went wrong.';
			exit;	
		}
		
		if(!$row_employer){
			echo 'Something went wrong.';
			exit;	
		}
		
		//Sending email to Jobseeker
		$row_email = $this->Email->get_records_by_id(7);
		$config = array();
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from($row_employer->email, $row_employer->first_name);
		$this->email->to($row_jobseeker->email);
		
		$this->email->subject($row_email->subject);
		$mail_message = $this->Email_drafts->send_message_to_candidate($row_email->content, $this->input->post('message'),$row_jobseeker, $row_employer);
		$this->email->message($mail_message);
		$this->email->send();	
		$this->session->set_userdata('timestm', date("H:i:s"));
		echo "done";
		exit;
	}

	public function im_interested_cv($applied_id = 0)
	{	
		$this->load->model('Recruitment_process');
		$this->load->model('Recruitment_candidate');
		
		$applied_id = $this->input->post('applied_id');
		$mark_value = $this->input->post('mark_value');

		$applied_job = $this->Applied_jobs->get_applied_job_by_id($applied_id);

		if (!$applied_job) {
			show_404();
		}

		$job = $this->Posted_job->get_posted_job_by_id($applied_job->job_ID);

		if (!$job) {
			show_404();
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

		if (user_belong_to_company_internal()) {
			$candidate_in_other_process = $this->Recruitment_candidate->is_candidate_active_in_other_process(
				$applied_job->job_ID, 
				$applied_job->seeker_ID
			);
			
			if ($candidate_in_other_process && $mark_value == 'yes') {
				echo json_encode(
					array('error' => '¡Este candidato ya está activo en otro proceso!')
				);	
				return; 
			}

			$candidate_in_blacklist = $this->check_candidate_in_blacklist_overall(
				$applied_job->document_number, 
				$mark_value
			);

			if ($candidate_in_blacklist) {	
				echo json_encode(
					array('error' => 'Este candiato está en la lista negra en Overall')
				);	

				return; 		
			}
		}

		$result_update = $this->Applied_jobs->mark_as_interesting_cv($applied_id, $mark_value);

		echo json_encode(array('success' => $result_update));
	}

	private function check_candidate_in_blacklist_overall($document_number, $mark_value)
	{
		if ($mark_value != 'yes' || empty($document_number)) {
			return false;
		}

		$blacklist = $this->Job_seeker->get_overall_blacklist([$document_number]);

    	return isset($blacklist[$document_number]) && 
		       ($blacklist[$document_number])->blacklist == 1; 
	}
}
