<?php 
class Jobseeker_entry_notify
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function notify($email = '')
    {
        $this->load->model('Entry_job_seeker');

        $seeker_entry = $this->Entry_job_seeker->get_job_seeker_by_email($email);
      
        if (!$seeker_entry) {
            return false;
        }

        $seeker = $this->Job_seeker->find($seeker_entry->seeker_id);

        if (!$seeker) {
            return false;
        }
        
        $password = create_random_password();

        $this->db->where('ID', $seeker_entry->seeker_id);
        $this->db->update('tbl_job_seekers', [
            'password' => do_hashing($password)
        ]);

        $status = $this->send_email($seeker_entry, $password);
    
        $job = $this->Posted_job->find($seeker_entry->for_job_ID);

        if ($job) {
            
            if ($seeker_entry->notify_by_mail == 1) {
                $this->load->library('Email/Recruitment_process/recruitment_process_candidate_add_email');
                $this->recruitment_process_candidate_add_email->send($job->request_ID, $seeker->ID);
            }
    
            if ($seeker_entry->notify_by_whatsapp == 1) {
                $this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_add_lib');
                $this->whatsapp_recruitment_proccess_candidate_add_lib->send($job->request_ID, $seeker->ID);
            }
        }

        if ($status) {
            $this->db->where('email', $seeker_entry->email);
            $this->db->where('seeker_id', $seeker_entry->seeker_id);
            $this->db->update('tbl_seeker_entries', [
                'notified' => 1,
                'error_log' => null
            ]);
        }
        
        return $status;
    }

    public function send_email($jobseeker = '', $password = '')
    {
		if (!$jobseeker) {
			return false;
		}

	    $data_email = [
            'jobseeker' => $jobseeker,
            'password' => $password,
            'url_link' => site_url('login')  
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($jobseeker->email);

        $mail_message = load_email_view('email/notify_entry_jobseeker', $data_email);

        $this->email->subject('Cuenta Postulante - Portal de empleo');
        $this->email->message($mail_message);     
     
        //Send email
        $status = $this->email->send();

        if (!$status) {

            $error_log = $this->email->print_debugger();

            $this->db->where('email', $jobseeker->email);
            $this->db->where('seeker_id', $jobseeker->seeker_id);
            $this->db->update('tbl_seeker_entries', [
                'error_log' => $error_log
            ]);
        }
        return $status;
    }
}
