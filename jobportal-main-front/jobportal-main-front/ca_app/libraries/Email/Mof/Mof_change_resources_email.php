<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mof_change_resources_email 
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($mof_id)
	{
		$mof = $this->Mof->find($mof_id);
		$created_by = $this->Employer->find($mof->created_by_user_id);

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Administrador';

	    $array_subject = [
	    	$mof->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Es necesario revisar los recursos del MOF, para aprobar los exámenes ocupacionales.',
			'mof' => $mof,
			'created_by' => $recruiter_name
		];

		$users = $this->Employer->get_internal_by_profile_id($mof->company_id, 4);
		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
			return;
		}

		$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
		$this->email->subject('MOF - Revisión de recursos - ' . $subject_profile);
		$this->email->message($mail_view);     
		//Send email
		$this->email->send();
	}
}
