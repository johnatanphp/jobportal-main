<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_layouts extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//Load models
		$this->load->model('Job_layout');
		$this->load->model('Work_experience');
		$this->load->model('Occupational_category');
		$this->load->model('Business_unit');
		$this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Workflow_cost_center');
		$this->load->model('Risk_criteria');
		$this->load->model('Company');
		$this->load->model('Country');
		$this->load->model('Sunat_code');
	}

	public function create()
	{	
		$this->form_validation->set_rules('sunat_code', 'Código SUNAT', 'trim|strip_all_tags');	
		$this->form_validation->set_rules('job_title', 'Nombre del cargo', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('risk_criteria', 'Criterio de riesgo', 'trim|required|strip_all_tags'); 
        $this->form_validation->set_rules('occupational_group', 'Grupo ocupacional', 'trim|required|strip_all_tags');   
		$this->form_validation->set_rules('education', 'Educación', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('study_grade_req', 'Grado de estudio deseable', 'trim|required');
		$this->form_validation->set_rules('education_req_detail', 'Más detalle', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('study_grade_min', 'Grado de estudio mínimo', 'trim|required');
		$this->form_validation->set_rules('education_min_detail', 'Descripción del empleo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('experience', 'Experiencia requerida', 'trim|required');
		$this->form_validation->set_rules('experience_detail', 'Más detalle', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('responsibilities[]', 'Responsabilidades', 'trim|required|strip_all_tags');

		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		if ($this->form_validation->run() === FALSE && $this->input->is_ajax_request()) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors()
			]);
			return;
        }
	
		$user = $this->Employer->find($this->session->userdata('user_id'));
		$company = $this->Company->find($user->company_ID);


		// VALIDACIÓN job_title duplicado
		$job_title = trim((string) $this->input->post('job_title'));

		$existe = $this->db
			->where('job_title', $job_title)
			->where('company_id', $user->company_ID)
			->count_all_results('tbl_job_layouts') > 0;

		if ($existe) {
			echo json_encode([
				'status'  => false,
				'message' => 'Este cargo ya existe en esta empresa.'
			]);
			return;
		}


		if ($this->form_validation->run() === FALSE) {
		
			$data['ads_row'] = $this->ads;
			$data['title'] = 'Crear layout de puesto - ' . SITE_NAME;
			$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
			$data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $company->ID]);
			$data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['results_disability'] = $this->Job_layout->get_results_disability_options();
			$data['factor_valuations'] = $this->Job_layout->get_factor_valuations();
			$data['benefits'] = $this->Job_layout->get_benefits($company->ID);
			$data['country'] = $this->Country->find($company->country_id);
			$data['sunat_codes'] = $this->Sunat_code->all(['state' => 'A']);

			$data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

			$this->load->view('employer/job_layouts/create', $data);
			return;
		}
		


		$all_inputs = $this->input->post();
		$all_inputs['company_id'] = $user->company_ID;

		$trans_id = $this->Job_layout->create_by_recruiter(
			$all_inputs
		);

		if ($trans_id !== false) {

			$this->session->set_flashdata('added_action', true);

			$this->notify_by_email($trans_id);

			// $this->load->library('Email/Job_layout/Job_layout_change_resources_email', null ,'Job_layout_change_resources_email');
			// $this->Job_layout_change_resources_email->send($trans_id);

			echo json_encode([
				'status' => true,
				'message' => 'Layout de puesto creado',
				'redirect_url' => site_url('employer/job_layouts/job_layouts/show/' . $trans_id)
			]);
			return;
		}

		echo json_encode([
			'status' => false,
			'message' => 'No se pudo crear el layout de puesto, por favor intente de nuevo'
		]);	
	}

	public function show($job_layout_id = 0)
	{
		$job_layout = $this->Job_layout->find($job_layout_id);

		if (!$job_layout) {
			show_404();
		}

		//Verificar si el usuario tiene los permisos para el Layout
		if (!$this->Job_layout->has_permission_employer($job_layout_id)) {
			show_404();
		}

		$company = $this->Company->find($job_layout->company_id);

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Mostrar layout de puesto - ' . SITE_NAME;
		$data['jl_occupational_group'] = $this->Job_charge->get_job_charge_by_id($job_layout->job_charge_id);
		$data['job_layout'] = $job_layout;
		$data['jl_skills'] = $this->Job_layout->get_skills_by_job_layout_id($job_layout_id);
		$data['jl_responsibilities'] = $this->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
		$data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $job_layout->job_charge_id
        ])->result();

        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

		//$data['jl_results_disability'] = $this->Job_layout->get_results_disability($job_layout_id);
		//$data['jl_disability_values'] = $this->Job_layout->disability_values($job_layout_id);

		$data['jl_disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
		$data['jl_disability_eligibles'] = $this->Job_layout->get_disability_eligibles($job_layout_id);
		$data['jl_factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
		$data['jl_factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
		$data['jl_benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
		$data['country'] = $this->Country->find($company->country_id);
		$data['sunat_code'] = $this->Sunat_code->get_sunat_code_by_code($job_layout->sunat_code);

		$this->load->view('employer/job_layouts/show', $data);
	}

	public function edit($job_layout_id = 0)
	{	
		$job_layout = $this->Job_layout->find($job_layout_id);
		
		if (!$job_layout) {
			show_404();
		}

		//Verificar si el usuario tiene los permisos para el Layout
		if (!$this->Job_layout->has_permission_employer($job_layout_id)) {
			show_404();
		}

		$this->form_validation->set_rules('sunat_code', 'Código SUNAT', 'trim|strip_all_tags');
		$this->form_validation->set_rules('job_title', 'Nombre del cargo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('risk_criteria', 'Criterio de riesgo', 'trim|required|strip_all_tags'); 
        $this->form_validation->set_rules('occupational_group', 'Grupo ocupacional', 'trim|required|strip_all_tags');   
		$this->form_validation->set_rules('education', 'Educación', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('study_grade_req', 'Grado de estudio deseable', 'trim|required');
		$this->form_validation->set_rules('education_req_detail', 'Más detalle', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('study_grade_min', 'Grado de estudio mínimo', 'trim|required');
		$this->form_validation->set_rules('education_min_detail', 'Descripción del empleo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('experience', 'Experiencia requerida', 'trim|required');
		$this->form_validation->set_rules('experience_detail', 'Más detalle', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('responsibilities[]', 'Responsabilidades', 'trim|required|strip_all_tags');

		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		if ($this->form_validation->run() === FALSE && $this->input->is_ajax_request()) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors()
			]);
			return;
        }

		$user = $this->Employer->find($this->session->userdata('user_id'));
		$company = $this->Company->find($user->company_ID);

		// VALIDACIÓN job_title duplicado (EDIT)
		$job_title = trim((string) $this->input->post('job_title'));

		$existe = $this->db
			->where('job_title', $job_title)
			->where('company_id', $user->company_ID)
			->where('id !=', $job_layout_id) // 👈 excluye el actual
			->count_all_results('tbl_job_layouts') > 0;

		if ($existe) {
			echo json_encode([
				'status'  => false,
				'message' => 'Este cargo ya existe en esta empresa.'
			]);
			return; // detiene el update
		}


		if ($this->form_validation->run() === FALSE) {

			$data['ads_row'] = $this->ads;
			$data['title'] = 'Editar layout de puesto - ' . SITE_NAME;
			$data['job_layout'] = $job_layout;
			$data['skills'] = $this->Job_layout->get_skills_by_job_layout_id($job_layout_id);
			$data['responsibilities'] = $this->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
			$data['benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
			$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
			$data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $company->ID]);
			$data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['country'] = $this->Country->find($company->country_id);
			$data['disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
			$data['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
			$data['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
			$data['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
			$data['sunat_codes'] = $this->Sunat_code->all(['state' => 'A']);	
			
			$data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

			$this->load->view('employer/job_layouts/edit', $data);
			return;
		}



		$all_inputs = $this->input->post();
		$all_inputs['active'] = 0;

		$old_resources = $this->Job_layout->get_resources($job_layout_id);
		$old_disability_grade = $this->Job_layout->disability_values($job_layout_id);
		
		$trans_status = $this->Job_layout->edit(
			$all_inputs,
			$job_layout_id
		);

		if ($trans_status !== false) {
			$this->session->set_flashdata('update_action', true);

			$this->notify_change_by_email($job_layout_id, $old_resources, $old_disability_grade);

			// if (count($this->Job_layout->get_change_resources($job_layout_id, $old_resources)) > 0) {

			// 	//Actualizar datos
			// 	$this->Job_layout->update($job_layout_id, ['occupational_exams_approved' => 0, 'active' => 0]);

			// 	//Notificar cambio
			// 	$this->load->library('Email/Job_layout/Job_layout_change_resources_email', null ,'Job_layout_change_resources_email');
			// 	$this->Job_layout_change_resources_email->send($job_layout_id);
			// }

			echo json_encode([
				'status' => true,
				'message' => 'Layout de puesto actualizado',
				'redirect_url' => site_url('employer/job_layouts/job_layouts/show/' . $job_layout_id)
			]);
			return;
		}

		echo json_encode([
			'status' => false,
			'message' => 'No se pudo editar el layout de puesto'
		]);
	}

	private function notify_by_email($job_layout_id)
	{
		$job_layout = $this->Job_layout->find($job_layout_id);
        
		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

		$array_subject = [
	    	$job_layout->job_title,
			$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Nuevo layout de puesto creado, se necesita la revisíon del mismo para su activación.',
			'job_layout' => $job_layout,
			'created_by' => $recruiter_name
		];
	
		//$data_view['disability_values'] = $this->Job_layout->disability_values($job_layout_id);
		$data_view['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
		$data_view['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
		$data_view['benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
		$data_view['structure_salary'] = true;

		$result_emails = $this->db->from('tbl_job_layout_alert_emails')
			->where('company_id', $job_layout->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}

		//Notificar alertas emails
		if (!empty($emails)) {
			$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Solicitud de layout de puesto - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
        }

		// //Notificar a SSO
        // $sso_emails = [];

        // $users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);

		// foreach ($users as $user) {
		// 	$sso_emails[] = $user->email;
		// }

        // if (!empty($sso_emails)) {
        //     $data_view['structure_salary'] = false;
        //     $mail_view = $this->load->view('email/job_layouts/notify_job_layout', $data_view, true);
    
        //     $config = $this->Email_drafts->email_configuration();
        //     $this->email->initialize($config);
        //     $this->email->clear(TRUE);
        //     $this->email->from(ADMIN_EMAIL, SITE_NAME);
        //     $this->email->to($sso_emails);
        //     $this->email->subject('Nuevo Layout de puesto - ' . $subject_profile);
        //     $this->email->message($mail_view);     
        //     //Send email
        //     $this->email->send();
        // }
	}

	private function notify_change_by_email(
		$job_layout_id, 
		$old_resorces, 
		$old_disability_grade
	)
	{
		$resource_emo = $this->Job_layout->get_resource_by_name('type_emo', $job_layout_id);
		$resource_exam_covid19 = $this->Job_layout->get_resource_by_name('exam_type_covid', $job_layout_id);

		$resource_change = [];
		
		if (@$resource_emo->resource_value != @($old_resorces['type_emo'])->resource_value) {
			$resource_change[] = 'EMO';
		}

		if (@$resource_exam_covid19->resource_value != @($old_resorces['exam_type_covid'])->resource_value) {
			$resource_change[] = 'COVID 19';
		}

		$job_layout = $this->Job_layout->find($job_layout_id);
		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));
		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

		$array_subject = [
	    	$job_layout->job_title
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Layout de puesto editado, es recomendable revisar los cambios realizados, para su activación',
			'job_layout' => $job_layout,
			'created_by' => $recruiter_name,
			'edited_by' => $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : ''
		];
		
		//$data_view['disability_values'] = $this->Job_layout->disability_values($job_layout_id);
		
		$data_view['disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
		$data_view['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
		$data_view['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
		$data_view['benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
		$data_view['structure_salary'] = true;
		
		if (count($resource_change) > 0) {
			$data_view['edit_fields'] = join(', ', $resource_change);
		};
		
		$disability_eligibles = $this->Job_layout->get_disability_eligibles($job_layout_id);

		if (count($disability_eligibles) > 0) {
			$data_view['disability_eligibles'] = $disability_eligibles;
		}

		$is_diff_section_disability_grade = $this->Job_layout->is_diff_section_disability_grade($job_layout_id, $old_disability_grade);

		if ($is_diff_section_disability_grade) {
			$data_view['disability_grade_edit'] =  true;
		}

		$result_emails = $this->db->from('tbl_job_layout_alert_emails')
			->where('company_id', $job_layout->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}

		if (!empty($emails)) {
			$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Edición layout de puesto - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}

		$users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);

		$emails = [];
		
		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (!empty($emails)) {	
			$data_view['structure_salary'] = false;
			$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);
		
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Edición de layout de puesto - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}
	}

	public function get_skills($job_charge_id = 0)
    {
        $skills = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $job_charge_id
        ])->result();

        $array_skills = [];

        foreach ($skills as $skill) {
            $array_skills[] = $skill->skill_name;
        }

        echo json_encode([
            'skills' => $array_skills
        ]);
    }

	public function edit_resources()
	{
		$resources = $this->input->post('resources');
		$job_layout_id = $this->input->post('id');

		$old_resorces['type_emo'] = $this->Job_layout->get_resource_by_name('type_emo', $job_layout_id);
		$old_resorces['exam_type_covid'] = $this->Job_layout->get_resource_by_name('exam_type_covid', $job_layout_id);

		foreach ($resources as $resource_type => $row) {

            $resource_value = isset($row['value']) ? $row['value'] : '';

            if ($resource_type == 'type_emo' ||
                $resource_type == 'exam_type_covid') {
                $resource_value =  implode(',', (array)$resource_value);
            }

            if ($resource_value == '') {
                continue;
            }

			$protocol_detail = '';

			if ($resource_type == 'type_emo' && in_array('PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE', $row['value'])) {
                $protocol_detail = $row['protocol_detail'];
            }

			$data_resource = [
				'resource_value' => $resource_value,
				'protocol_detail' => $protocol_detail
			];
	
			$this->db->where('job_layout_id', $job_layout_id);
			$this->db->where('resource', $resource_type);
			$this->db->update('tbl_job_layout_resources', $data_resource);
		}

		$this->notify_change_resources_by_email($job_layout_id, $old_resorces);

		echo json_encode([
			'success' => true
		]);
	}

	private function notify_change_resources_by_email($job_layout_id, $old_resorces)
	{
		$job_layout = $this->Job_layout->find($job_layout_id);

		if (!$job_layout_id) {
			return;
		}

		$resource_emo = $this->Job_layout->get_resource_by_name('type_emo', $job_layout_id);
		$resource_exam_covid19 = $this->Job_layout->get_resource_by_name('exam_type_covid', $job_layout_id);

		$resource_change = [];
		
		if ($resource_emo->resource_value != ($old_resorces['type_emo'])->resource_value) {
			$resource_change[] = 'EMO';
		}

		if ($resource_exam_covid19->resource_value != ($old_resorces['exam_type_covid'])->resource_value) {
			$resource_change[] = 'COVID 19';
		}

		if (count($resource_change) == 0) {
			return;
		}
		
		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));
	
		$job_layout = $this->Job_layout->find($job_layout_id);

		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

		$array_subject = [
	    	$job_layout->job_title
	    ];

	    $subject_profile = join(" ", $array_subject);
		$edit_fields = join(', ', $resource_change);

		$data_view = [
			'body' => 'Recursos del layout de puesto editado, es recomendable revisar los cambios realizados.',
			'job_layout' => $job_layout,
			'created_by' => $recruiter_name,
			'edited_by' => $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '',
			'edit_fields' => $edit_fields,
			'disability_values' => $this->Job_layout->disability_values($job_layout_id),
			'disability_eligibles' => $this->Job_layout->get_disability_eligibles($job_layout_id),
			'factor_valuations' => $this->Job_layout->get_factor_valuations($job_layout_id),
			'factor_total_score' => $this->Job_layout->get_factor_total_score($job_layout_id),
			'structure_salary' => true
		];

		//Notificar a correos configurados en alertas
		$result_emails = $this->db->from('tbl_job_layout_alert_emails')
			->where('company_id', $job_layout->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}

		if (!empty($emails)) {
			$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Cambio recursos layout de puesto - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
        }

		//Notificar a usuarios con el perfil SSO
		$users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (!empty($emails)) {
			$data_view['structure_salary'] = false;
			$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);
	
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Cambio recursos layout de puesto - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}
	}

	public function save_disability_elegibles()
	{
		$data = $this->input->post('disability_eligibles');
		$job_layout_id = $this->input->post('id');

		$job_layout = $this->Job_layout->find($job_layout_id);

		$this->Job_layout->save_disability_eligibles($data, $job_layout_id);

		//Notificar a administradores
		$result_emails = $this->db->from('tbl_job_layout_alert_emails')
			->where('company_id', $job_layout->company_id)
			->get()
			->result();

		$alert_emails = [];

		foreach ($result_emails as $row) {
			$alert_emails[] = $row->email;
		}
		
		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

	    $array_subject = [
	    	$job_layout->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Se ha agregado discapacidades aptas para el layout de puesto',
			'job_layout' => $job_layout,
			'created_by' => $recruiter_name,
			'edited_by' => $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '',
			'disability_values' => $this->Job_layout->disability_values($job_layout_id),
			'disability_eligibles' => $this->Job_layout->get_disability_eligibles($job_layout_id),
			'structure_salary' => true
		];
		
		$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);

		if (!empty($alert_emails)) {
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($alert_emails);
			$this->email->subject('Agregar/Edición Discapacidades aptas Layout de puesto - ' . $job_layout->job_title);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}

		//Notificar a usuarios con el el perfil SSO
		$users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (!empty($emails)) {
			$data_view['structure_salary'] = false;

			$mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);
		
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Agregar/Edición Discapacidades aptas Layout de puesto - ' . $job_layout->job_title);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}

		echo json_encode([
			'success' => true
		]);
	}

	public function occupational_exams_approved()
	{
		$id = $this->input->post('id');
		$approved = $this->input->post('approved');

		$jl = $this->Job_layout->find($id);

		if (!$jl || $jl->active) {
			echo json_encode([
				'success' => true
			]);
		}

		$this->Job_layout->update($id, ['occupational_exams_approved' => $approved]);

		echo json_encode([
			'success' => true
		]);
	}

	public function validate_job_title()
	{
		$employer = $this->Employer->find($this->session->userdata('user_id'));
		$company_id = $employer->company_ID;
		$id = 0;

		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$job_title = trim($this->input->post('job_title'));
		//$id        = $this->input->post('id'); // null en create
		//$company_id = $this->session->userdata('company_id');

		$this->db->where('job_title', $job_title);
		$this->db->where('company_id', $company_id);

		// Para edición (excluir el mismo registro)
		if (!empty($id)) {
			$this->db->where('id !=', $id);
		}

		$existe = $this->db->count_all_results('tbl_job_layouts') > 0;

		echo json_encode([
			'existe' => $existe
		]);
	}

	public function validate_job_title_edit($id)
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$job_title = trim((string) $this->input->post('job_title'));

		if ($job_title === '') {
			echo json_encode(['existe' => false]);
			return;
		}

		$employer   = $this->Employer->find($this->session->userdata('user_id'));
		$company_id = $employer->company_ID;

		$existe = $this->db
			->where('job_title', $job_title)
			->where('company_id', $company_id)
			->where('id !=', $id) // 👈 excluye el actual
			->count_all_results('tbl_job_layouts') > 0;

		echo json_encode([
			'existe' => $existe
		]);
	}


	public function get_job_layout_info()
	{
		$job_layout = $this->Job_layout->find($this->input->get('id'));
		$job_layout->benefits = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout->id);
		$job_layout->skills = $this->Job_layout->get_skills_by_job_layout_id($job_layout->id);
		$job_layout->responsibilities = $this->Job_layout->get_responsibilities_by_job_layout_id($job_layout->id);

		echo json_encode([
			'data' => $job_layout,
			'status' => true
		]);
	}

	public function update_sts()
    {
		if (!has_permission_action('job_layouts', 'active_inactive')) {
			echo json_encode([
				'status' => false,
				'message' => 'Utsed no tiene permitido esta acción'
			]);
			return;
		}

		$params = $this->input->post();
        $job_layout_id = $params['id'];
		$status = $params['sts'] ?? 0;

        $trans_sts = $this->Job_layout->update($job_layout_id, [
			'active' => $status ? 1 : 0
		]);

        echo json_encode([
            'status' => $trans_sts,
            'message' => 'OK'
        ]);
    }
}
