<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_process_candidate_to_payroll_system_email 
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($request_id, $candidate_id)
	{
        $candidate = $this->Job_seeker->find($candidate_id);

        if (!$candidate) {
            return;
        }

        $request = $this->Staff_request->find($request_id);

        if (!$request) {
            return;
        }

        $employer = $this->Employer->find($request->recruiter_ID);

        if (!$employer) {
            return;
        }
        // Plantilla candidato enviado a sistema de nomina
        $this->db->where('id', 10);
        $template_email = $this->db->get('tbl_email_applicant_phase')->row();

        if (!$template_email) {
            return;
        }

        $job = $this->Posted_job->find([
            'request_ID' => $request->ID
        ]);

        $this->db->select([
            'jobs.job_slug AS job_slug',
            'jobs.job_title AS job_title'
        ]);
        $this->db->from('tbl_recruitment_candidates recruitment_candidate');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=recruitment_candidate.job_ID');
        $this->db->where('recruitment_candidate.job_ID', $job->ID);
        $this->db->where('recruitment_candidate.seeker_ID', $candidate->ID);
        $recruitment_candidate = $this->db->get()->row();

        if (!$recruitment_candidate) {
            return;
        }
     
		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($employer->email);
		$this->email->subject($template_email->subject);
		$this->email->message($this->build_message_view($recruitment_candidate, $candidate, $employer, $template_email));     
		//Send email
		$this->email->send();
	}

    public function build_message_view($recruitment_candidate, $candidate, $employer, $template_email)
    {       
        $body = str_replace([
            '{{candidate_doc_number}}', 
            '{{candidate_name}}', 
            '{{job_title}}',
            '{{employer_name}}',
            '{{url_link}}',
        ],
        [
            $candidate->document_number,
            $candidate->first_name,
            $recruitment_candidate->job_title,
            $employer->first_name,
            site_url('login')
        ],
            $template_email->body
        );
    
        $data_email = [
            'body' => $body
        ];
        
        return load_email_view('email/notify_stage_candidate', $data_email);
    }
}
