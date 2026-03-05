<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_profile_change_resources_email 
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($jp_id)
	{
		$jp = $this->Job_profile->find($jp_id);
		$created_by = $this->Employer->find($jp->created_by_recruiter_ID);

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Administrador';

	    $array_subject = [
	    	$jp->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Es necesario revisar los recursos del perfil laboral, para aprobar los exámenes ocupacionales.',
			'job_profile' => $jp,
			'created_by' => $recruiter_name
		];

		$users = $this->Employer->get_internal_by_profile_id($jp->company_id, 4);
		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
			return;
		}

		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
		$this->email->subject('Perfil laboral - Revisión de recursos - ' . $subject_profile);
		$this->email->message($mail_view);     
		//Send email
		$this->email->send();
	}
}
