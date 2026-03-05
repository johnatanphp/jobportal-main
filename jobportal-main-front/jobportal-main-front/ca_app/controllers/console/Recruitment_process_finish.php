<?php
require_once ("App_console.php");

class Recruitment_process_finish extends App_console  
{
    public function __construct()
    {
        parent::__construct();  

        $this->load->model('Staff_request');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Recruitment_process');
    }

    public function run()
    {
        $date_today = date('Y-m-d');
        //$date_today = '2024-11-27';

        $this->db->select([
            'process.id AS process_id',
            'process.job_ID AS job_id',
            'jobs.job_title AS job_title',
            'requests.ID AS request_id',
            'process.expiration_date AS process_expiration_date',
        ]);
        $this->db->from('tbl_recruitment_process process');
        $this->db->join('tbl_post_jobs jobs', 'process.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=jobs.request_ID');
        $this->db->where('process.sts!=', 'finished');
        $this->db->where('process.expired', '0');
        $this->db->where('process.expiration_date', $date_today);
        $this->db->order_by('process.expiration_date', 'ASC');
        $this->db->limit('25');
        $results = $this->db->get()->result();

        foreach ($results as $process) {
            $this->finish_process($process);
        }
    }

    public function finish_process($process)
    {   
        $this->Recruitment_process->update_process_sts($process->job_id, [
            'sts' => 'finished',
            'expired' => 1
        ]);
        
        $this->db->where('ID', $process->job_id);
        $this->db->update('tbl_post_jobs', [
            'sts' => 'inactive'
        ]);
    
        $emails = $this->Staff_request->get_emails_assigned_employers($process->request_id);

        $count_candidates = $this->Recruitment_candidate->count_all_candidates_by_stage(
            $process->job_id, 
            -1// Todas las etapas
        );

		$data_email = [
			'process' => $process,
            'url_link' => site_url('employer/recruitment_processes/show_process/' . $process->job_id),
            'count_candidates' => $count_candidates
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);

		$mail_message = load_email_view('email/recruitment/recruitment_finish', $data_email);

		$this->email->subject('Proceso de reclutamiento expirado - ' . $process->job_title);
		$this->email->message($mail_message);     
		$this->email->send();	
    }
}