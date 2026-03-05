<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidate_registration_email 
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($candidate_id)
	{
        $candidate = $this->Job_seeker->find($candidate_id);

        // Plantilla de registro candidato
        $this->db->where('id', 4);
        $template_email = $this->db->get('tbl_email_applicant_phase')->row();

        if (!$template_email) {
            return;
        }
     
		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($candidate->email);
		$this->email->subject($template_email->subject);
		$this->email->message($this->build_message_view($candidate, $template_email));     
		//Send email
		$this->email->send();
	}

    public function build_message_view($candidate, $template_email)
    {   
        $body = str_replace([ 
            '{{candidate_name}}', 
            '{{url_link}}',
        ],
        [
            $candidate->first_name,
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
