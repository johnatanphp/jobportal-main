<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profiles extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//Load models
		$this->load->model('Job_profile');
		$this->load->model('Work_experience');
		$this->load->model('Occupational_category');
		$this->load->model('Business_unit');
		$this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Workflow_cost_center');
		$this->load->model('Risk_criteria');
		$this->load->model('Company');
		$this->load->model('Country');
	}

	public function create()
	{
		if (!$this->config->item('job_profile_module_enabled')) {
			show_404();
		}

		$this->form_validation->set_rules('sunat_code', 'Código SUNAT', 'trim|strip_all_tags');
		$this->form_validation->set_rules('consultant_name', 'Consultora', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('business_unit_name', 'Unidad de negocio', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('client_company_name', 'Empresa cliente', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');

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

		$user = $this->Employer->find($this->session->userdata('user_id'));
		$company = $this->Company->find($user->company_ID);

		if ($this->form_validation->run() === FALSE) {

			$data['ads_row'] = $this->ads;
			$data['title'] = 'Crear Perfil de puesto - ' . SITE_NAME;
			$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
			$data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $company->ID]);
			$data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['results_disability'] = $this->Job_profile->get_results_disability();
			$data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations();
			$data['jp_benefits'] = $this->Job_profile->get_benefits($company->ID);
			$data['country'] = $this->Country->find($company->country_id);

			$data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

			$this->load->view('employer/job_profile/create', $data);
			return;
		}

		$all_inputs = $this->input->post();
		$all_inputs['company_id'] = $user->company_ID;

		$trans_id = $this->Job_profile->create_by_recruiter(
			$all_inputs
		);

		if ($trans_id !== false) {

			$this->session->set_flashdata('added_action', true);
			
			$this->load->library('Email/Job_profile/Job_profile_change_resources_email', null ,'Job_profile_change_resources_email');
			$this->Job_profile_change_resources_email->send($trans_id);

			$this->notify_by_email($trans_id);
			
			redirect('employer/job_profiles/profiles/show/' . $trans_id);
			return;
		}

		$this->session->set_flashdata('added_action', false);
		redirect('employer/job_profiles/profiles/create');
	}

	public function show($job_profile_id = 0)
	{
		$job_profile = $this->Job_profile->get_job_profile_by_id($job_profile_id);

		if (!$job_profile) {
			show_404();
		}

		$company = $this->Company->find($job_profile->company_id);

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Mostrar perfil laboral - ' . SITE_NAME;
		$data['job_profile'] = $job_profile;
		$data['job_profile_skills'] = $this->Job_profile->get_skills_by_job_profile_id($job_profile_id);
		$data['job_profile_responsibilities'] = $this->Job_profile->get_responsibilities_by_job_profile_id($job_profile_id);
		$data['occupational_group'] = $this->Job_charge->get_job_charge_by_id($job_profile->job_charge_ID);
		$data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $job_profile->job_charge_ID
        ])->result();

        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

		$data['results_disability'] = $this->Job_profile->get_results_disability($job_profile_id);
		$data['disability_values'] = $this->Job_profile->disability_values($job_profile_id);
		$data['disability_eligibles'] = $this->Job_profile->get_disability_eligibles($job_profile_id);
		$data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
		$data['jp_factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);
		$data['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);
		$data['country'] = $this->Country->find($company->country_id);
		
		$this->load->view('employer/job_profile/show', $data);
	}

	public function edit($job_profile_id = 0)
	{
		if (!$this->config->item('job_profile_module_enabled')) {
			show_404();
		}

		$job_profile = $this->Job_profile->get_job_profile_by_id($job_profile_id);
		
		if (!$job_profile) {
			show_404();
		}

		$this->form_validation->set_rules('consultant_name', 'Consultora', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('business_unit_name', 'Unidad de negocio', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('client_company_name', 'Empresa cliente', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');		
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

		$user = $this->Employer->find($this->session->userdata('user_id'));
		$company = $this->Company->find($user->company_ID);

		if ($this->form_validation->run() === FALSE) {

			$data['ads_row'] = $this->ads;
			$data['title'] = 'Editar perfil del puesto - ' . SITE_NAME;
			$data['mof'] = $job_profile;
			$data['mof_skills'] = $this->Job_profile->get_skills_by_job_profile_id($job_profile_id);
			$data['mof_responsibilities'] = $this->Job_profile->get_responsibilities_by_job_profile_id($job_profile_id);
			$data['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);

			$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
			$data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $company->ID]);
			$data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['country'] = $this->Country->find($company->country_id);
			
            $data['consultants'] = $this->Workflow_consultant->get_all($user->company_ID);

			$data['clients'] = $this->Workflow_client->get_all(
				$user->company_ID,  
				$job_profile->no_cia, 
				$job_profile->cod_business_unit
			);

			$data['cost_centers'] = $this->Workflow_cost_center->get_all($user->company_ID, 
				$job_profile->no_cia, 
				$job_profile->cod_clie,
				$job_profile->cod_business_unit
			);
			
			$data['results_disability'] = $this->Job_profile->get_results_disability($job_profile_id);
			$data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
			$data['jp_factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);	
			
			$data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

			$this->load->view('employer/job_profile/edit', $data);
			return;
		}

		$all_inputs = $this->input->post();
		$all_inputs['active'] = 0;

		$old_resources = $this->Job_profile->get_resources($job_profile_id);
		$old_disability_grade = $this->Job_profile->disability_values($job_profile_id);
		
		$trans_status = $this->Job_profile->edit(
			$all_inputs,
			$job_profile_id
		);

		if ($trans_status !== false) {
			$this->session->set_flashdata('update_action', true);

			if (count($this->Job_profile->get_change_resources($job_profile_id, $old_resources)) > 0) {

				//Actualizar datos
				$this->Job_profile->update($job_profile_id, ['occupational_exams_approved' => 0, 'active' => 0]);

				//Notificar cambio
				$this->load->library('Email/Job_profile/Job_profile_change_resources_email', null ,'Job_profile_change_resources_email');
				$this->Job_profile_change_resources_email->send($job_profile_id);
			}

			$this->notify_change_by_email($job_profile_id, $old_resources, $old_disability_grade);
		}

		redirect('employer/job_profiles/profiles/show/' . $job_profile_id);
	}

	private function notify_by_email($job_profile_id)
	{
		$job_profile = $this->Job_profile->find($job_profile_id);

    	$result_emails = $this->db->from('tbl_job_profile_alert_emails')
			->where('company_id', $job_profile->company_id)
			->get()
			->result();

    	$emails = [];

    	foreach ($result_emails as $row) {
    		$emails[] = $row->email;
    	}
        
        if (empty($emails)) {
        	return;
        }
    
		$created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));
		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

		$array_subject = [
	    	$job_profile->client_company_name,
	    	$job_profile->consultant_name,
	    	$job_profile->business_unit_name,
	    	$job_profile->cost_center,
	    	$job_profile->job_title,
			$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Nuevo perfil laboral creado, se necesita la revisíon del mismo para su activación.',
			'job_profile' => $job_profile,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit
		];
	
		$data_view['disability_values'] = $this->Job_profile->disability_values($job_profile_id);
		$data_view['factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
		$data_view['factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);
		$data_view['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);
		$data_view['structure_salary'] = true;
		
		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Solicitud de Perfil laboral - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
	}

	private function notify_change_by_email(
		$job_profile_id, 
		$old_resorces, 
		$old_disability_grade
	)
	{
		$resource_emo = $this->Job_profile->get_resource_by_name('type_emo', $job_profile_id);
		$resource_exam_covid19 = $this->Job_profile->get_resource_by_name('exam_type_covid', $job_profile_id);

		$resource_change = [];
		
		if (@$resource_emo->resource_value != @($old_resorces['type_emo'])->resource_value) {
			$resource_change[] = 'EMO';
		}

		if (@$resource_exam_covid19->resource_value != @($old_resorces['exam_type_covid'])->resource_value) {
			$resource_change[] = 'COVID 19';
		}

		if (count($resource_change) > 0) {
			$data_view['edit_fields'] = join(', ', $resource_change);
		};

		$job_profile = $this->Job_profile->find($job_profile_id);
		$created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));
		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

		$array_subject = [
	    	$job_profile->client_company_name,
	    	$job_profile->consultant_name,
	    	$job_profile->business_unit_name,
	    	$job_profile->cost_center,
	    	$job_profile->job_title
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Perfil laboral editado, es recomendable revisar los cambios realizados, para su activación',
			'job_profile' => $job_profile,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit
		];
		
		$data_view['disability_values'] = $this->Job_profile->disability_values($job_profile_id);
		$data_view['factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
		$data_view['factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);
		$data_view['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);
		$data_view['structure_salary'] = true;
		
		$disability_eligibles = $this->Job_profile->get_disability_eligibles($job_profile_id);

		if (count($disability_eligibles) > 0) {
			$data_view['disability_eligibles'] = $disability_eligibles;
		}

		$is_diff_section_disability_grade = $this->Job_profile->is_diff_section_disability_grade($job_profile_id, $old_disability_grade);

		if ($is_diff_section_disability_grade) {
			$data_view['disability_grade_edit'] =  true;
		}

		$result_emails = $this->db->from('tbl_job_profile_alert_emails')
			->where('company_id', $job_profile->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}

		if (!empty($emails)) {
			$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Edición de Perfil laboral - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}

		$users = $this->Employer->get_internal_by_profile_id($job_profile->company_id, 4);

		$emails = [];
		
		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		$data_view['structure_salary'] = false;
		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
		$this->email->subject('Edición de Perfil laboral - ' . $subject_profile);
		$this->email->message($mail_view);     
		//Send email
		$this->email->send();
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
		$job_profile_id = $this->input->post('job_profile_id');

		$old_resorces['type_emo'] = $this->Job_profile->get_resource_by_name('type_emo', $job_profile_id);
		$old_resorces['exam_type_covid'] = $this->Job_profile->get_resource_by_name('exam_type_covid', $job_profile_id);

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
	
			$this->db->where('job_profile_ID', $job_profile_id);
			$this->db->where('resource', $resource_type);
			$this->db->update('tbl_job_profile_resources', $data_resource);
		}

		$this->notify_change_resources_by_email($job_profile_id, $old_resorces);

		echo json_encode([
			'success' => true
		]);
	}

	private function notify_change_resources_by_email($job_profile_id, $old_resorces)
	{
		$job_profile = $this->Job_profile->get_job_profile_by_id($job_profile_id);

		if (!$job_profile_id) {
			return;
		}

		$resource_emo = $this->Job_profile->get_resource_by_name('type_emo', $job_profile_id);
		$resource_exam_covid19 = $this->Job_profile->get_resource_by_name('exam_type_covid', $job_profile_id);

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
		
		$created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));
	
    	$result_emails = $this->db->from('tbl_job_profile_alert_emails')
			->where('company_id', $job_profile->company_id)
			->get()
			->result();

    	$emails = [];

    	foreach ($result_emails as $row) {
    		$emails[] = $row->email;
    	}
        
        if (empty($emails)) {
        	return;
        }
    
		$job_profile = $this->Job_profile->find($job_profile_id);

		$created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

		$array_subject = [
	    	$job_profile->client_company_name,
	    	$job_profile->consultant_name,
	    	$job_profile->business_unit_name,
	    	$job_profile->cost_center,
	    	$job_profile->job_title
	    ];

	    $subject_profile = join(" ", $array_subject);
		$edit_fields = join(', ', $resource_change);

		$data_view = [
			'body' => 'Recursos del perfil laboral editado, es recomendable revisar los cambios realizados.',
			'job_profile' => $job_profile,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit,
			'edit_fields' => $edit_fields,
			'disability_values' => $this->Job_profile->disability_values($job_profile_id),
			'disability_eligibles' => $this->Job_profile->get_disability_eligibles($job_profile_id),
			'factor_valuations' => $this->Job_profile->get_factor_valuations($job_profile_id),
			'factor_total_score' => $this->Job_profile->get_factor_total_score($job_profile_id),
			'structure_salary' => true
		];
		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Cambio recursos Perfil laboral - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();

		//Notificar a usuarios con el el perfil SSO
		$users = $this->Employer->get_internal_by_profile_id($job_profile->company_id, 4);

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
        	return;
		}

		$data_view['structure_salary'] = false;
		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Cambio recursos Perfil laboral - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
	}

	public function save_disability_elegibles()
	{
		$data = $this->input->post('disability_eligibles');
		$job_profile_id = $this->input->post('job_profile_id');

		$job_profile = $this->Job_profile->find($job_profile_id);

		$this->Job_profile->save_disability_eligibles($data, $job_profile_id);

		//Notificar a administradores
		$result_emails = $this->db->from('tbl_job_profile_alert_emails')
			->where('company_id', $job_profile->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}
		
		if (empty($emails)) {
			return;
		}

		$created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

	    $array_subject = [
	    	$job_profile->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Se ha agregado discapacidades aptas para el perfil laboral',
			'job_profile' => $job_profile,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit,
			'disability_values' => $this->Job_profile->disability_values($job_profile_id),
			'disability_eligibles' => $this->Job_profile->get_disability_eligibles($job_profile_id),
			'structure_salary' => true
		];
		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Agregar/Edición Discapacidades aptas Perfil laboral - ' . $job_profile->job_title);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();

		//Notificar a usuarios con el el perfil SSO
		$users = $this->Employer->get_internal_by_profile_id($job_profile->company_id, 4);

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
        	return;
		}

		$data_view['structure_salary'] = false;

		$mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Agregar/Edición Discapacidades aptas Perfil laboral - ' . $job_profile->job_title);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();

		echo json_encode([
			'success' => true
		]);
	}

	public function occupational_exams_approved()
	{
		$id = $this->input->post('id');
		$approved = $this->input->post('approved');

		$jp = $this->Job_profile->find($id);

		if (!$jp || $jp->active) {
			echo json_encode([
				'success' => true
			]);
		}

		$this->Job_profile->update($id, ['occupational_exams_approved' => $approved]);

		echo json_encode([
			'success' => true
		]);
	}
}
