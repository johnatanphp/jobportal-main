<?php

class Recruitment_notify_assignment_hiring_email
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct(){}

    public function send($job_id, $seeker_ids = [])
    {
        $job = $this->Posted_job->find($job_id);
		
		//Obtener usuarios RRHH asignados al proceso
		$rrhh_users = $this->db->select([
            'app_users.email',
            'app_users.first_name',
            'app_users.last_name',
        ])
			->from('tbl_recruitment_rrhh_assignments rrhh_assignment')
			->join('tbl_employers app_users', 'rrhh_assignment.rrhh_user_ID=app_users.ID')
			->where('rrhh_assignment.job_ID', $job_id)
			->get()
			->result();
        
        $rrhh_emails = [];

        foreach ($rrhh_users as $rrhh_user) {
			$rrhh_emails[] = $rrhh_user->email;
        }

		if (count($seeker_ids) > 0) {
			
			$staff_request = $this->db->get_where('tbl_staff_requests', [
				'ID' => $job->request_ID 
			])->row();

			$cost_center_users = $this->db->select([
				'users.email'
			])
				->from('tbl_rrhh_cost_centers rrhh_cost_centers')
				->join('tbl_employers users', 'users.ID=rrhh_cost_centers.user_id')
				->where('users.company_ID', $job->company_ID)
				->where('rrhh_cost_centers.cost_center', $staff_request->cost_center)
				->where('users.sts', 'active')
				->get()
				->result();

			foreach ($cost_center_users as $rrhh_user) {
				$rrhh_emails[] = $rrhh_user->email;
			}
		}

		$data_email = [
			'job' => $job,
			'url_link' => site_url('login')  
        ];

		if (count($rrhh_emails) > 0) {
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($rrhh_emails);
			$mail_message = load_email_view('email/notify_rrhh_users_finished_process', $data_email);

			$this->email->subject('Seguimiento contratación para RRHH: #' . $job->ID . ' - ' . $job->job_title);
			$this->email->message($mail_message);     
			//Send email
			$this->email->send();
		}

		if (count($seeker_ids) == 0) {
			return;
		}

		//Verificar si los candidatos movidos del proceso son extrajeros
		$this->db->from('tbl_recruitment_candidates rc')
			->join('tbl_job_seekers seeker', 'seeker.ID=rc.seeker_ID')
			->where('seeker.document_type!=', '1')
			->where('rc.contracted', 0)
			->where('rc.job_ID', $job_id)
			->where_in('seeker.ID', $seeker_ids);
			
		$count_seeker_foreign = $this->db->count_all_results();

		if ($count_seeker_foreign == 0) {
			return;
		}

		//Obtener usuarios de contabilidad para que verifiquen
		//el Record migratorio vigente de los postulantes extrangeros
		$users = $this->db->select([
			'users.email'
		])
			->from('tbl_employers users')
			->join(
				'tbl_employer_profiles user_profiles', 
				'user_profiles.user_id=users.ID'
			)
			->where('user_profiles.profile_id', 6) //Perfil contabilidad perfil id 6
			->where('users.sts', 'active')
			->get()
			->result();

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (count($emails) > 0) {
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$mail_message = load_email_view('email/rys_accounting_users_notification', $data_email);

			$this->email->subject('Verificación Record migratorio vigente: #' . $job->ID . ' - ' . $job->job_title);
			$this->email->message($mail_message);     
			//Send email
			$this->email->send();
		}
    }
}
