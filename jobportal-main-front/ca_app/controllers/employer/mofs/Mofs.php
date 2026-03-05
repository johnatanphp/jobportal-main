<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mofs extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//Load models
		$this->load->model('Internal_area');
        $this->load->model('Mof');
        $this->load->model('Job_charge');
        $this->load->model('Work_experience');
		$this->load->model('Occupational_category');
		$this->load->model('Risk_criteria');
		$this->load->model('Country');
	}

    public function index()
    {
    	redirect('employer/mofs/mofs/search');
    }

    public function search()
    {
    	$data['ads_row'] = $this->ads;
		$data['title'] = 'Mof creados ' . SITE_NAME;

		$user = $this->Employer->find($this->session->userdata('user_id'));

		$filters = array(
			'query' => trim((string)$this->input->get('query', true)),
			'status' => trim((string)$this->input->get('status', true)),
			'area_id' => trim((string)$this->input->get('area_id', true)),
			'company_id' => $user->company_ID
		);

		if ($this->session->userdata('current_profile_id') == 2) {
			$filters['permission_area_ids'] = $this->Employer->get_allowed_areas($user->ID);
			$filters['permission_job_charge_ids'] = $this->Employer->get_allowed_job_charges($user->ID);
		}

		$total_rows = $this->Mof->count_all_by_user($filters);

		$config = pagination_configuration(
			pagination_url(), 
			$total_rows, 
			$this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
			3, 
			5, 
			true,
			true,
			true
		);

		//$page = ($this->uri->segment(2)) ? $this->uri->segment(3) : 0;
        $page = ($this->input->get('page') ? (int)$this->input->get('page') : 0);
		$per_page = $config['per_page'];
		$page_num = $page - 1;
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $per_page;

		$results = $this->Mof->search_all_by_user($filters, $per_page, $page);

		$data['belonging_areas'] = $this->Internal_area->get_all_active_areas();
		$data['links'] = $this->pagination->create_links();	
		$data['results'] = $results;
		$data['total_rows'] = $total_rows;
		$data['filters'] = $filters;

		$this->load->view('employer/mof/mofs', $data);
    }
	
	public function create()
	{
		if (!$this->config->item('mof_module_enabled')) {
			show_404();
		}

		$this->form_validation->set_rules('sunat_code', 'Código SUNAT', 'trim|strip_all_tags');	
		$this->form_validation->set_rules('job_title', 'Nombre del cargo', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('risk_criteria', 'Criterio de riesgo', 'trim|required|strip_all_tags'); 
        $this->form_validation->set_rules('belonging_areas[]', 'Áreas perteneciente', 'trim|required|strip_all_tags');
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
			$data['title'] = 'Crear MOF - ' . SITE_NAME;
			$data['country'] = $this->Country->find($company->country_id);
			$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
			$data['belonging_areas'] = $this->Internal_area->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['results_disability'] = $this->Mof->get_results_disability();
			$data['mof_factor_valuations'] = $this->Mof->get_factor_valuations();
			$data['mof_benefits'] = $this->Mof->get_benefits($user->company_ID);

			$data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

			$this->load->view('employer/mof/create', $data);
			return;
		}

		$all_inputs = $this->input->post();
		// dd($all_inputs);
		$all_inputs['company_id'] = $user->company_ID;
		$trans_id = $this->Mof->create_by_recruiter(
			$all_inputs
		);

		if ($trans_id) {
			$this->session->set_flashdata('added_action', true);

			$this->load->library('Email/Mof/Mof_change_resources_email', null ,'Mof_change_resources_email');
			$this->Mof_change_resources_email->send($trans_id);

			$this->notify_by_email($trans_id);

			redirect('employer/mofs/mofs/show/' . $trans_id);
			return;
		}

		$this->session->set_flashdata('added_action', false);
		redirect('employer/mofs/mofs/create');
	}

	public function edit($mof_id = 0)
	{
		if (!$this->config->item('mof_module_enabled')) {
			show_404();
		}

		$mof = $this->Mof->find($mof_id);
		
		if (!$mof) {
			show_404();
		}

		$this->form_validation->set_rules('sunat_code', 'Código SUNAT', 'trim|strip_all_tags');	
		$this->form_validation->set_rules('job_title', 'Nombre del cargo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('risk_criteria', 'Criterio de riesgo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('belonging_areas[]', 'Áreas perteneciente', 'trim|required|strip_all_tags');
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
			$data['title'] = 'Editar MOF - ' . SITE_NAME;
			$data['mof'] = $mof;
			$data['country'] = $this->Country->find($company->country_id);
			$data['mof_skills'] = $this->Mof->get_skills_by_mof_id($mof_id);
			$data['mof_responsibilities'] = $this->Mof->get_responsibilities_by_mof_id($mof_id);
			$data['mof_belonging_areas'] = $this->Mof->get_belonging_areas_by_mof_id($mof_id);
			$data['mof_indicators'] = $this->Mof->get_indicators_by_mof_id($mof_id);
			$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
			$data['belonging_areas'] = $this->Internal_area->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);

			$data['results_disability'] = $this->Mof->get_results_disability($mof_id);
			$data['mof_factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
			$data['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);
			
			$data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

			$this->load->view('employer/mof/edit', $data);
			return;
		}

		$all_inputs = $this->input->post();

		$old_resources = $this->Mof->get_resources($mof_id);
		$old_disability_grade = $this->Mof->disability_values($mof_id);
		$all_inputs['active'] = 0;

		$trans_status = $this->Mof->edit(
			$all_inputs,
			$mof_id
		);

		if ($trans_status !== false) {
			$this->session->set_flashdata('update_action', true);
		
			if (count($this->Mof->get_change_resources($mof_id, $old_resources)) > 0) {

				//Actualizar datos
				$this->Mof->update($mof_id, ['occupational_exams_approved' => 0, 'active' => 0]);

				//Notificar cambio
				$this->load->library('Email/Mof/Mof_change_resources_email', null ,'Mof_change_resources_email');
				$this->Mof_change_resources_email->send($mof_id);
			}

			$this->notify_change_by_email($mof_id, $old_resources, $old_disability_grade);
		}

		redirect('employer/mofs/mofs/show/' . $mof_id);
	}

	public function show($mof_id)
	{	
		$mof = $this->Mof->get_mof_by_id($mof_id);

        if (!$mof) {
            show_404();
        }

		$company = $this->Company->find($mof->company_id);

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Mostrar MOF - ' . SITE_NAME;
        $data['mof'] = $mof;
		$data['country'] = $this->Country->find($company->country_id);
        $data['mof_belonging_areas'] = $this->Mof->get_belonging_areas_by_mof_id($mof_id);
        $data['mof_skills'] = $this->Mof->get_skills_by_mof_id($mof_id);
        $data['mof_responsibilities'] = $this->Mof->get_responsibilities_by_mof_id($mof_id);
        $data['mof_indicators'] = $this->Mof->get_indicators_by_mof_id($mof_id);
        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $mof->job_charge_id
        ])->result();

		$data['results_disability'] = $this->Mof->get_results_disability($mof_id);
		$data['disability_values'] = $this->Mof->disability_values($mof_id);
		$data['disability_eligibles'] = $this->Mof->get_disability_eligibles($mof_id);
		$data['mof_factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
		$data['mof_factor_total_score'] = $this->Mof->get_factor_total_score($mof_id);
		$data['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);

		$data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];
	
        $this->load->view('employer/mof/show', $data);
	}

	private function notify_by_email($mof_id)
	{
		$mof = $this->Mof->get_mof_by_id($mof_id);
		
		$created_by = $this->Employer->find($mof->created_by_user_id);

    	$result_emails = $this->db->from('tbl_mof_alert_emails')
			->where('company_id', $mof->company_id)
			->get()
			->result();

    	$emails = [];

    	foreach ($result_emails as $row) {
    		$emails[] = $row->email;
    	}
        
        if (empty($emails)) {
        	return;
        }

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		
	    $array_subject = [
	    	$mof->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Nuevo MOF creado, se necesita la revisíon del mismo para su activación.',
			'mof' => $mof,
			'created_by' => $recruiter_name
		];

		$data_view['disability_values'] = $this->Mof->disability_values($mof_id);
		$data_view['factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
		$data_view['factor_total_score'] = $this->Mof->get_factor_total_score($mof_id);
		$data_view['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);
		$data_view['structure_salary'] = true;
	
		$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('MOF creado - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
	}

	private function notify_change_by_email(
		$mof_id, 
		$old_resorces, 
		$old_disability_grade)
	{
		$mof = $this->Mof->get_mof_by_id($mof_id);
		$created_by = $this->Employer->find($mof->created_by_user_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));
	

		$resource_emo = $this->Mof->get_resource_by_name('type_emo', $mof_id);
		$resource_exam_covid19 = $this->Mof->get_resource_by_name('exam_type_covid', $mof_id);

		$resource_change = [];
		
		if (@$resource_emo->resource_value != @($old_resorces['type_emo'])->resource_value) {
			$resource_change[] = 'EMO';
		}

		if (@$resource_exam_covid19->resource_value != @($old_resorces['exam_type_covid'])->resource_value) {
			$resource_change[] = 'COVID 19';
		}

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

	    $array_subject = [
	    	$mof->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'MOF ha sido editado, se necesita la revisíon del mismo para su activación.',
			'mof' => $mof,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit
		];

		if (count($resource_change) > 0) {
			$data_view['edit_fields'] = join(', ', $resource_change);
		};
	
		$data_view['disability_values'] = $this->Mof->disability_values($mof_id);
		$data_view['factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
		$data_view['factor_total_score'] = $this->Mof->get_factor_total_score($mof_id);
		$data_view['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);
		$data_view['structure_salary'] = true;
	
		$disability_eligibles = $this->Mof->get_disability_eligibles($mof_id);

		if (count($disability_eligibles) > 0) {
			$data_view['disability_eligibles'] = $disability_eligibles;
		}

		$is_diff_section_disability_grade = $this->Mof->is_diff_section_disability_grade($mof_id, $old_disability_grade);

		if ($is_diff_section_disability_grade) {
			$data_view['disability_grade_edit'] =  true;
		}
	
		$emails = [];
		
		$result_emails = $this->db->from('tbl_mof_alert_emails')
			->where('company_id', $mof->company_id)
			->get()
			->result();

    	foreach ($result_emails as $row) {
    		$emails[] = $row->email;
    	}

		if (!empty($emails)) {
			$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);
			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('MOF editado - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}

		$users = $this->Employer->get_internal_by_profile_id($mof->company_id, 4);
		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
			return;
		}

		$data_view['structure_salary'] = false;
		$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
		$this->email->subject('MOF editado - ' . $subject_profile);
		$this->email->message($mail_view);     
		//Send email
		$this->email->send();
	}

	public function edit_resources()
	{
		$resources = $this->input->post('resources');
		$mof_id = $this->input->post('mof_id');

		$old_resorces['type_emo'] = $this->Mof->get_resource_by_name('type_emo', $mof_id);
		$old_resorces['exam_type_covid'] = $this->Mof->get_resource_by_name('exam_type_covid', $mof_id);
		
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
	
			$this->db->where('mof_ID', $mof_id);
			$this->db->where('resource', $resource_type);
			$this->db->update('tbl_mof_resources', $data_resource);
		}

		$this->notify_change_resources_by_email($mof_id, $old_resorces);

		echo json_encode([
			'success' => true
		]);
	}

	private function notify_change_resources_by_email($mof_id, $old_resorces)
	{
		$mof = $this->Mof->find($mof_id);

		if (!$mof) {
			return;
		}
	
		$resource_emo = $this->Mof->get_resource_by_name('type_emo', $mof_id);
		$resource_exam_covid19 = $this->Mof->get_resource_by_name('exam_type_covid', $mof_id);

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

		$created_by = $this->Employer->find($mof->created_by_user_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

	    $array_subject = [
	    	$mof->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);
	    
		$edit_fields = join(', ', $resource_change);

		$data_view = [
			'body' => 'Recursos MOF editado, es recomendable revisar los cambios realizados.',
			'mof' => $mof,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit,
			'edit_fields' => $edit_fields,
			'disability_values' => $this->Mof->disability_values($mof_id),
			'disability_eligibles' => $this->Mof->get_disability_eligibles($mof_id),
			'factor_valuations' => $this->Mof->get_factor_valuations($mof_id),
			'factor_total_score' => $this->Mof->get_factor_total_score($mof_id),
			'structure_salary' => true
		];

		//Notificar a administradores
		$result_emails = $this->db->from('tbl_mof_alert_emails')
			->where('company_id', $mof->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}
		
		if (!empty($emails)) {
			$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($emails);
			$this->email->subject('Edición Recursos MOF - ' . $subject_profile);
			$this->email->message($mail_view);     
			//Send email
			$this->email->send();
		}
		//Notificar a usuarios con el el perfil SSO
		$users = $this->Employer->get_internal_by_profile_id($mof->company_id, 4);

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
        	return;
		}

		$data_view['structure_salary'] = false;

		$config = $this->Email_drafts->email_configuration();
		$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Edición Recursos MOF - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
	}

	public function save_disability_elegibles()
	{
		$data = $this->input->post('disability_eligibles');
		$mof_id = $this->input->post('mof_id');

		$mof = $this->Mof->find($mof_id);
		
		$this->Mof->save_disability_eligibles($data, $mof_id);

		//Notificar a administradores
		$result_emails = $this->db->from('tbl_mof_alert_emails')
			->where('company_id', $mof->company_id)
			->get()
			->result();

		$emails = [];

		foreach ($result_emails as $row) {
			$emails[] = $row->email;
		}
		
		if (empty($emails)) {
			return;
		}

		$created_by = $this->Employer->find($mof->created_by_user_id);
		$edited_by = $this->Employer->find($this->session->userdata('user_id'));

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';
		$recruiter_name_edit = $edited_by ? $edited_by->first_name . ' ' . $edited_by->last_name : '';

	    $array_subject = [
	    	$mof->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Se ha agregado discapacidades aptas para el MOF',
			'mof' => $mof,
			'created_by' => $recruiter_name,
			'edited_by' => $recruiter_name_edit,
			'disability_values' => $this->Mof->disability_values($mof_id),
			'disability_eligibles' => $this->Mof->get_disability_eligibles($mof_id),
			'structure_salary' => true
		];
		$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Agregar/Edición Discapacidades aptas MOF - ' . $mof->job_title);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();

		//Notificar a usuarios con el el perfil SSO
		$users = $this->Employer->get_internal_by_profile_id($mof->company_id, 4);

		$emails = [];

		foreach ($users as $user) {
			$emails[] = $user->email;
		}

		if (empty($emails)) {
        	return;
		}

		$data_view['structure_salary'] = false;
		$mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);
	    $this->email->subject('Agregar/Edición Discapacidades aptas MOF - ' . $mof->job_title);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();

		echo json_encode([
			'success' => true
		]);
	}

	public function occupational_exams_approved()
	{
		$mof_id = $this->input->post('id');
		$approved = $this->input->post('approved');

		$mof = $this->Mof->find($mof_id);

		if (!$mof || $mof->active) {
			echo json_encode([
				'success' => true
			]);
		}

		$this->Mof->update($mof_id, ['occupational_exams_approved' => $approved]);

		echo json_encode([
			'success' => true
		]);
	}

	public function get_mof_info()
	{
		$mof = $this->Mof->find($this->input->get('id'));
		$mof->benefits = $this->Mof->get_benefits($mof->company_id, $mof->ID);

		echo json_encode([
			'mof' => $mof,
			'success' => true
		]);
	}
}
