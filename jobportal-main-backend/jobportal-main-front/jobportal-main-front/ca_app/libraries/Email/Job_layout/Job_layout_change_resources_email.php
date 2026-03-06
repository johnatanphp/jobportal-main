<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_layout_change_resources_email 
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($id)
	{
		$job_layout = $this->Job_layout->find($id);
		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

	    $array_subject = [
	    	$job_layout->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Es necesario revisar los recursos del layout de puesto, para aprobar los exámenes ocupacionales.',
			'job_layout' => $job_layout,
			'created_by' => $recruiter_name
		];

		$users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);
		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
			return;
		}

		$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
		$this->email->subject('Layout de puesto - Revisión de recursos - ' . $subject_profile);
		$this->email->message($mail_view);     
		//Send email
		$this->email->send();
	}
}
