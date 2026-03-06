<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_document_requests_auth_login extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

	public function index()
	{
		$token = trim($this->input->get('t'));

		if (empty($token)) {
			show_404();
		}

		$this->db->from('tbl_recruitment_document_requests');
		$this->db->where('token', $token);
		$token_link = $this->db->get()->row();

		if (!$token_link) {
			show_404();
		}

		$now = new DateTime('now');
		$token_datetime = new DateTime($token_link->token_created_at);
		$minutes = abs($now->getTimestamp() - $token_datetime->getTimestamp()) / 60;

		//Si el link pasa de 2 dia de creado expira
		if ($minutes > 2880) {

			$data['employer'] = $this->Employer->find($token_link->created_by);

			$this->load->view(
				'employer/recruitment_entry/recruitment_document_requests/document_link_expired', 
				$data
			);
			return;
		}

		$seeker = $this->Job_seeker->find($token_link->seeker_id);

		$this->load->library('App/Session/Session_job_seeker');
		
		$this->session_job_seeker->logout();
		$this->session_job_seeker->create($seeker);
		
		$this->session->set_userdata('menu', 0);
		$this->session->set_userdata('request_documents', true);
		$this->session->set_userdata('request_documents_link_id', $token_link->id);

		redirect('jobseeker/requested_documents?menu=0');
	}
}
