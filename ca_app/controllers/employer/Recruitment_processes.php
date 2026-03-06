<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_processes extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		$this->load->model('Recruitment_process');
		$this->load->model('Recruitment_candidate');
		$this->load->model('Candidate_interview');
		$this->load->model('Recruitment_period_rule');
		$this->load->model('Identity_document_type');
		$this->load->model('Civil_status');
		$this->load->model('Disability');
		$this->load->model('Gender');
		$this->load->model('Recruitment_stage');
		$this->load->model('Rrhh_responsible');
		$this->load->model('Staff_request');
    }

    public function index($job_id = '', $stage = '')
	{
		if (empty($job_id)) {
			show_404();
		}

		$process = $this->Recruitment_process->find(['job_ID' => $job_id]);
		$process_id = '';

		if ($process) {
			redirect('employer/recruitment_processes/show_process/' . $job_id . '/' . $process->id);	
			return;
		}

		redirect('employer/recruitment_processes/show_process/' . $job_id);	
	}

    public function show_process($job_id = 0, $process_id = 0, $stage = null)
    {
		$data['ads_row'] = $this->ads;

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
			exit;
		}

		$data['title'] = 'RyS - ' . $job->job_title . ' - ' . SITE_NAME;

		//Get employer in sesion
        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		if (!$process_id) {
			$process = $this->Recruitment_process->find(['job_ID' => $job_id]);

			if ($process) {
				redirect('employer/recruitment_processes/show_process/' . $job_id . '/' . $process->id);	
				return;
			}
		}
		
		$staff_request = $this->Staff_request->find($job->request_ID);

		// Si el proceso proviene de una solicitud con a los modelos que no sean 1, 2 y 3 
		// No mostrar
		if ($staff_request && !in_array($staff_request->request_model_id, [1, 2, 3])) {
			$this->load->view('employer/recruitment/process_not_available', $data);
			return;
		}
		
		// Si el proceso proviene de una empleo que es ignorado
		// No mostrar
		if ($job->job_ignore == 1) {
			$this->load->view('employer/recruitment/process_not_available', $data);
			return;
		}

		$rs_process = $this->Recruitment_process->get_process_by_id($process_id);
		
		if (!$rs_process) {
			$stage = 0;
		}

		if (($stage != -1 && !$this->Recruitment_stage->find(['id' => $stage])) || $stage === null) {
			$stage = $rs_process->sts_stage;
		}

		$company = $this->Company->find($obj_employer->company_ID);

		$data['job'] = $job;
		$data['rs_process'] = $rs_process;
		$data['rs_process_created_by'] = $this->Employer->get_employer_by_id($rs_process->created_by ?? null);
		$data['current_stage'] = $stage;
		$data['alert_period_rules'] = $this->Recruitment_period_rule->all();
		$data['result_countries'] = $this->Country->get_all_countries();
		$data['departments'] = $this->Ubigeo->get_all_departments();
		$data['document_types'] = $this->Identity_document_type->all(['country_id' => $company->country_id, 'active' => 1]);
		$data['civil_status'] = $this->Civil_status->all(['active' => 1]);
		$data['disabilities'] = $this->Disability->all(['active' => 1]);
		$data['genders'] = $this->Gender->all(['active' => 1]);
		$data['country'] = $this->Country->find($company->country_id);
		$data['ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);

		$this->load->view('employer/recruitment/process_show', $data);
    }

	public function get_candidates()
	{	
		$job_id = $this->input->get('job_id', true);

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

		//Get employer in sesion
        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

		$rs_process = $this->Recruitment_process->get_process_by_job_id($job_id);
		
		if (!$rs_process) {
			show_404();
		}

        $candidates = $this->db->select([
			'job_seeker.id',
			'job_seeker.document_number',
			'doc_types.abbreviation AS document_type_abbr',
            'job_seeker.first_name',
           	'job_seeker.last_name',
           	'job_seeker.email',
           	'job_seeker.mobile',
			'job_seeker.dob',
			'job_seeker.city',
           	'stages.name AS stage_name',
           	'rs_candidate.discarded'
        ])
		->from('tbl_recruitment_candidates rs_candidate')
		->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID')
		->join('tbl_recruitment_stages stages', 'stages.id=rs_candidate.stage')
		->join('tbl_identity_document_types doc_types', 'doc_types.id=job_seeker.document_type', 'left')
		->where('rs_candidate.job_ID', $job_id)
		->get()
		->result();

		echo json_encode([
			'data' => $candidates
		]);
	}

	public function open_process()
	{
		$this->form_validation->set_rules('job_id', 'Empleo id', 'trim|required');
		$this->form_validation->set_rules('stages[]', 'Etapas', 'trim|strip_all_tags');

		if ($this->form_validation->run() === FALSE && count($this->input->get()) > 0) {
			$params = $this->input->get();
			
			$job_id = $params['job_id'];
			$job = $this->Posted_job->find($job_id);
			$data = [
				'job_id' => $params['job_id'],
				'job' => $job,
				'stages' => $this->Recruitment_stage->all(['active' => 1, 'stage_group_id' => 1])
			];
			$this->load->view('employer/recruitment/common/form_open_process', $data);
			return;
		}

		$params = $this->input->post();
		$job_id = $params['job_id'];
		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

		//Get employer in sesion
        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$process = $this->Recruitment_process->find(['job_ID' => $job_id]);
	
		if ($process) {
			echo json_encode([
				'status' => false,
				'message' => 'Ya existe un proceso abierto para este empleo'
			]);
			return;
		}

		$data_open_process = [
            'job_ID' => $job_id,
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $obj_employer->ID,
			'expiration_date' => isset($params['apply_expiration_date']) && $params['apply_expiration_date'] == '1' ? $params['expiration_date'] : null,
            'sts' => 'active'
		];

        $insert_status = $this->db->insert('tbl_recruitment_process', $data_open_process);

		if (!$insert_status) {
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo abrir el proceso'
			]);
			return;
		}

		$process_id = $this->db->insert_id();

		if (!$process_id) {
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo abrir el proceso'
			]);
			return;
		}
		
		$stages = $params['stages'] ?? [];
		$stages = array_merge($stages, [6, 7]);

		foreach ($stages as $stage_id) {
			$this->db->insert('tbl_recruitment_process_stages', [
				'job_id' => $job_id,
				'stage_id' => $stage_id
			]);
		}

		$this->Recruitment_process->update_process_stage($process_id);

		//Asignar rrhh empleadores automaticamente
		$this->Staff_request->assign_rrhh_responsibles($job->request_ID);
		
		echo json_encode([
			'status' => true,
			'data' => [
				'id' => $process_id,
				'process_url' => site_url('employer/recruitment_processes/show_process/' . $job_id . '/' . $process_id)
			],
			'message' => 'Ok'
		]);
	}

	public function suspend_process()
	{        
		$this->form_validation->set_rules('job_id', 'Empleo id', 'trim|required');
		$this->form_validation->set_rules('note', 'Nota / Observaciones', 'trim|required|strip_all_tags');

		if ($this->form_validation->run() === FALSE) {
			
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos sin ingresar'
			));
			return;
		}
	
		$job_id = $this->input->post('job_id');
		$note = $this->input->post('note');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$trans_status = $this->Recruitment_process->suspend_process(
			$job_id, 
			$note
		);

		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function resume_process()
	{
		$job_id = $this->input->post('job_id');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
			exit;
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }
		
		$trans_status = $this->Recruitment_process->resume_process($job_id);

		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function finish_process()
	{
		$this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
		//$this->form_validation->set_rules('rrhh_user_ids[]', 'RRHH Usuario Ids', 'trim|required');
		$this->form_validation->set_rules('note', 'Nota / Observaciones', 'trim|required|strip_all_tags');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos sin ingresar'
			));
			return;
		}

		$job_id = $this->input->post('job_id');
		$note = $this->input->post('note');
		$rrhh_user_ids = $this->input->post('rrhh_user_ids');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
			exit;
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }
		
		if (user_belong_to_company_internal()) { 

			$trans_status = $this->Recruitment_process->finish_process(
				$job_id, 
				$note, 
				$rrhh_user_ids
			);
			
			if ($trans_status) {
				$this->send_email_to_candidates_not_selected($job_id);

				$this->load->Library(
					'Email/Recruitment/Recruitment_notify_assignment_hiring_email', 
					null, 
					'Recruitment_notify_assignment_hiring_email'
				);
		
				$this->Recruitment_notify_assignment_hiring_email->send($job_id);
			}

		} else {

			$trans_status = $this->Recruitment_process->finish_process_external(
				$job_id, 
				$note
			);
		}

		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function send_terna_short_list()
	{
		$emails = $this->input->post('emails') ? $this->input->post('emails') : [];

		$this->form_validation->set_rules('process_id', 'Proceso Id', 'trim|required');
		$this->form_validation->set_rules('emails[]', 'emails', 'trim|required|valid_email');

		foreach ($emails as $index => $email) {
			$this->form_validation->set_rules('emails[' . $index . ']', 'Emails', 'trim|required|valid_email');
		}

		if ($this->form_validation->run() === FALSE) {
			$message_error = $this->form_validation->error_array();
			echo json_encode([
				'success' => false,
				'message' => current($message_error)
			]);
			return;
		}

		$to_emails = [];

		foreach ($emails as $email) {
			$to_emails[] = trim($email);
		}

		$process_id = $this->input->post('process_id');
		$process = $this->Recruitment_process->find($process_id);
		
		if (!$process) {
    		echo json_encode([
    			'success' => false,
    			'message' => 'Proceso no es valido'
    		]);
    		return;
		}
		
		$job = $this->Posted_job->get_posted_job_by_id($process->job_ID);

		$staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

		$token = create_token(70);

		$register_token = $this->db->insert('tbl_recruitment_short_list_tokens', [
			'process_id' => $process_id,
			'token' => $token,
			'created_at' => date('Y-m-d H:i:s')
		]);

		if (!$register_token) {
			echo json_encode([
				'success' => false,
				'message' => 'No se pudo registrar el token'
			]);
			return;
		}
		
		$data_email = [
			'name' => $staff_request->recruiter_first_name,
			'url_link' => site_url('employer/recruitment_short_list/candidates/show_process/' . $process_id . '?t=' . $token),	
			'staff_request' => $staff_request,
			'token' => $token
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($to_emails);
		$mail_message = load_email_view('email/candidates_short_list_sending', $data_email);

		$this->email->subject('Terna / Short List - Solicitud de personal');
		$this->email->message($mail_message);     
		$this->email->send();
	
		echo json_encode([
			'success' => true,
			'message' => 'Terna o Short list ha sido enviada'
		]);
	}
    
	private function send_email_to_candidates_not_selected($job_id)
    {
        $job_id = $this->input->post('job_id');
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        
        //Obtener todos los candidatos no seleccionados del proceso 
        $candidates = $this->Recruitment_candidate->get_candidates_no_selected_by_job_id($job_id);
        
        foreach ($candidates as $row_candidate) {
        
            $data_email = array(
                'name' => $row_candidate->first_name,
                'job' => $job,  
            );

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($row_candidate->email);

            $mail_message = load_email_view('email/message_candidate_not_selected', $data_email);

            $this->email->subject('Gracias por tu participación');
            $this->email->message($mail_message);     
            $this->email->send();
        }
    }

	public function update_email() {
	
		if (!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		$seeker_id = trim($this->input->post('id'));

		$this->form_validation->set_rules(
			'email', 
			'email', 
			'trim|required|valid_email|is_unique[tbl_job_seekers.email]|strip_all_tags',
			['is_unique' => 'El email ' . $this->input->post('email') . ' ya está  en uso por una cuenta de un candidato, por favor ingrese un correo diferente.']
		);
	
		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'success' => false,
				'message' => validation_errors()
			]);
			exit;
		}

		
		$profile_array = [
			'email' => $this->input->post('email'),
		];
		
		$update = $this->Job_seeker->update($seeker_id, $profile_array);
		
		echo json_encode([
			'success' => true,
			'message' => '¡El email ha sido actualizado!'
		]);
	}

	public function update_phone()
	{
		$this->form_validation->set_rules('id', 'Postulante', 'required');
		$this->form_validation->set_rules('full_mobile_phone_number', 'Teléfono', 'required|is_valid_phone_number');
		
		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'success' => false,
				'message' => validation_errors()
			));
			return;
		}

		$seeker_id = $this->input->post('id');

		$profile_array = [
			'mobile' => $this->input->post('full_mobile_phone_number'),
		];
		
		$update = $this->Job_seeker->update($seeker_id, $profile_array);
		
		echo json_encode(array(
			'success' => true,
			'message' => 'Teléfono actualizado'
		));
	}

	public function update_birthdate()
	{
		$this->form_validation->set_rules('date', 'date', 'required');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$seeker_id = $this->input->post('id');

		$profile_array = array(
			'dob'		=> $this->input->post('date'),
		);
		
		$update = $this->Job_seeker->update($seeker_id, $profile_array);
		
		echo json_encode(array(
			'success' => true
		));
	}

	public function update_city()
	{
		$this->form_validation->set_rules('id', 'Postulante ID', 'trim|required');
		$this->form_validation->set_rules('city', 'Ubicación', 'trim|required');
		
		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'success' => false,
				'message' => validation_errors()
			));
			return;
		}

		$seeker_id = $this->input->post('id');

		$profile_array = [
			'city' => $this->input->post('city')
		];
		
		$update = $this->Job_seeker->update($seeker_id, $profile_array);
		
		echo json_encode([
			'success' => true,
			'message' => 'Ubicación actualizada'
		]);
	}

	public function update_seeker_data($candidate_id = 0)
	{
		$candidate = $this->Job_seeker->find($candidate_id);

		$this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required');
		$this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
		
		if ($this->form_validation->run() === FALSE && count($this->input->post()) == 0) {
			$data = [
				'candidate' => $candidate,
				'civil_status' => $this->Civil_status->all(['active' => 1]),
				'genders' => $this->Gender->all(['active' => 1])
			];
			$this->load->view('employer/recruitment/modal/update_data_seeker_form', $data);
			return;
		}

		if ($this->form_validation->run() === FALSE && count($this->input->post()) > 0) {
			echo json_encode([
				'status' => false,
				'message' => 'Hay datos incorrectos'
			]);
			return;
		}

		$params = $this->input->post();
		$profile_data = [	
			'first_name' => trim($params['first_name']),
			'paternal_last_name' => trim($params['paternal_last_name']),
			'maternal_last_name' => trim($params['maternal_last_name']),
			'last_name' => trim($params['paternal_last_name'] . ' ' . $params['maternal_last_name']),
			'civil_status' => $params['civil_status'],
			'gender' => $params['gender']
		];
		
		$update = $this->Job_seeker->update($candidate_id, $profile_data);
		
		echo json_encode([
			'status' => $update ? true : false,
			'message' => 'Datos actualizados'
		]);
	}

	public function resume_process_expired()
	{
		$this->form_validation->set_rules('job_id', 'Proceso Id', 'trim|required');
		$this->form_validation->set_rules('expiration_date', 'Fecha expiracion', 'trim|required');
		
		if ($this->form_validation->run() === FALSE && count($this->input->get()) > 0) {
			$params = $this->input->get();

			$job_id = $params['job_id'];
			$process = $this->Recruitment_process->get_process_by_job_id($job_id);

			$data = [
				'process' => $process
			];
			$this->load->view('employer/recruitment/common/form_resume_process_expired', $data);
			return;
		}

		$params = $this->input->post();
		$job_id = $params['job_id'];

		$trans_status = $this->Recruitment_process->resume_process($job_id);

		if (!$trans_status) {
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo reanudar el proceso'
			]);
			return;
		}

		$data_process = [
			'expiration_date' => $params['expiration_date'],
			'expired' => 0
		];

		$this->db->where('job_ID', $job_id);
        $update_status = $this->db->update('tbl_recruitment_process', $data_process);

		if (!$update_status) {
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo actualizar la fecha de expiracion del proceso'
			]);
			return;
		}

		echo json_encode([
			'status' => true,
			'message' => 'Fecha de expiración actualizada'
		]);
	}
}
