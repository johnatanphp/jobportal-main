<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_profiles extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Job_profile');
        $this->load->model('Job_charge');
        $this->load->model('Work_experience');
        $this->load->model('Occupational_category');
        $this->load->model('Company');
        $this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Workflow_cost_center');
        $this->load->model('Business_unit');
        $this->load->model('Risk_criteria');
        $this->load->model('Country');
    }
    
    public function index()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Listado de perfiles creados - ' . SITE_NAME;

        $filters = [
            'query' => trim((string)$this->input->get('query', true)),
            'requested' => trim((string)$this->input->get('requested', true)),
            'status' => trim((string)$this->input->get('status', true)),
            'company_id' => trim((string)$this->input->get('company_id') != '' ? $this->input->get('company_id') : '1') 
        ];

        $total_rows = 0;

        $config = pagination_configuration(pagination_url(), $total_rows, 25, 3, 5, true, true, true);

        $this->pagination->initialize($config);
        $page = ($this->input->get('page') ? (int)$this->input->get('page') : 0);
        $per_page = $config['per_page'];
        $page_num = $page - 1;
        $page_num = ($page_num < 0) ? '0' : $page_num;
        $page = $page_num * $per_page;

        $results = [];

        $data['links'] = $this->pagination->create_links(); 
        $data['results'] = $results;
        $data['total_rows'] = $total_rows;
        $data['filters'] = $filters;
        $data['companies'] = $this->Company->get_all_internal();

        $this->load->view('admin/job_profile/job_profile_list', $data);
    }

    public function search()
    {
        $params = $this->input->get();

        $this->db->select([
            'job_profile.*',
            'c.company_name'
        ]);
        $this->db->from('tbl_job_profiles job_profile');
        $this->db->join('tbl_companies c', 'c.ID=job_profile.company_id');

        if ($params['company_id']) {
            $this->db->where('job_profile.company_id', $params['company_id']);
        }

        if (isset($params['requested']) && $params['requested'] != '') {
            $this->db->where('requested', $params['requested']);
        }

        if (isset($params['status']) && $params['status'] != '') {
            $this->db->where('active', $params['status']);
        }

        $this->db->order_by('job_profile.active', 'DESC');
        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function create()
    {
        if ($this->config->item('jp_edit_404')) {
            show_404();
        }
        
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
        $this->form_validation->set_rules('responsibilities[]', 'Responsabilidades', 'trim|strip_all_tags');
        
        $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

        $company_id = $this->input->get('company_id');
        $company = $this->Company->find($company_id);

        if ($this->form_validation->run() === FALSE) {

            $data['ads_row'] = $this->ads;
            $data['title'] = 'Crear Perfil de puesto - ' . SITE_NAME;

            $data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations();
            $data['results_disability'] = $this->Job_profile->get_results_disability();
            $data['jp_benefits'] = $this->Job_profile->get_benefits($company_id);

            $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => @$company->country_id]);
            $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => @$company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => @$company->country_id]);
            $data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => @$company->ID]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => @$company->country_id]);

            $data['occupational_categories'] = $this->Occupational_category->get_all(['active' => 1]);
            $data['companies'] = $this->Company->get_all_internal();
            $data['country'] = $this->Country->find(@$company->country_id);
            $data['company_id'] = $company_id;

            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];
            
            $this->load->view('admin/job_profile/create', $data);
            return;
        }

        $all_inputs = $this->input->post();
        $trans_id = $this->Job_profile->create(
            $all_inputs
        );

        if ($trans_id !== false) {
            $this->session->set_flashdata('added_action', true);
            
            $this->load->library('Email/Job_profile/Job_profile_change_resources_email', null ,'Job_profile_change_resources_email');
			$this->Job_profile_change_resources_email->send($trans_id);
            
            $this->notify_by_email($trans_id);
        }

        redirect('admin/job_profiles/show/' . $trans_id);
    }

    public function edit($job_profile_id)
    {
        if ($this->config->item('jp_edit_404')) {
            show_404();
        }
        
        $job_profile = $this->Job_profile->find($job_profile_id);
    
        if (!$job_profile) {
            show_404();
        }

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
        $this->form_validation->set_rules('responsibilities[]', 'Responsabilidades', 'trim|strip_all_tags');
        
        $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

        $company_id = $job_profile->company_id;
        $company = $this->Company->find($company_id);

        if ($this->form_validation->run() === FALSE) {

            $data['ads_row'] = $this->ads;
            $data['title'] = 'Editar perfil del puesto - ' . SITE_NAME;
            $data['mof'] = $job_profile;
            $data['country'] = $this->Country->find($company->country_id);
            $data['mof_skills'] = $this->Job_profile->get_skills_by_job_profile_id($job_profile_id);
            $data['mof_responsibilities'] = $this->Job_profile->get_responsibilities_by_job_profile_id($job_profile_id);
            $data['results_disability'] = $this->Job_profile->get_results_disability($job_profile_id);
            $data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
            $data['jp_factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);
            $data['jp_benefits'] = $this->Job_profile->get_benefits($company_id, $job_profile_id);

            $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
            $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $company->ID]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['company'] = $company;

            $data['consultants'] = $this->Workflow_consultant->get_all($company->ID);
            $data['clients'] = $this->Workflow_client->get_all(
                $company->ID, 
                $job_profile->no_cia, 
                $job_profile->cod_business_unit
            );

            $data['cost_centers'] = $this->Workflow_cost_center->get_all(
                $company->ID, 
                $job_profile->no_cia, 
                $job_profile->cod_clie, 
                $job_profile->cod_business_unit
            );

            $data['occupational_categories'] = $this->Occupational_category->get_all(['active' => 1]);

            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

            $this->load->view('admin/job_profile/edit', $data); 
            return;
        }

        $all_inputs = $this->input->post();

        $old_resources = $this->Job_profile->get_resources($job_profile_id);
        $old_disability_values = $this->Job_profile->disability_values($job_profile_id);

        $trans_status = $this->Job_profile->edit(
            $all_inputs,
            $job_profile_id
        );

        if ($trans_status) {
            $this->session->set_flashdata('update_action', true);

            if (count($this->Job_profile->get_change_resources($job_profile_id, $old_resources)) > 0) {

				//Actualizar datos
				$this->Job_profile->update($job_profile_id, ['occupational_exams_approved' => 0, 'active' => 0]);

				//Notificar cambio
				$this->load->library('Email/Job_profile/Job_profile_change_resources_email', null ,'Job_profile_change_resources_email');
				$this->Job_profile_change_resources_email->send($job_profile_id);
			}

            $this->notify_change_by_email($job_profile_id, $old_disability_values);
        }

        redirect('admin/job_profiles/show/' . $job_profile_id);
    }

    public function show($job_profile_id)
    {
        $job_profile = $this->Job_profile->find($job_profile_id);

        if (!$job_profile) {
            show_404();
        }

        $company = $this->Company->find($job_profile->company_id);
      
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Mostrar perfil laboral - ' . SITE_NAME;
        $data['mof'] = $job_profile;
        $data['country'] = $this->Country->find($company->country_id);
        $data['mof_skills'] = $this->Job_profile->get_skills_by_job_profile_id($job_profile_id);
        $data['mof_responsibilities'] = $this->Job_profile->get_responsibilities_by_job_profile_id($job_profile_id);
        
        $data['results_disability'] = $this->Job_profile->get_results_disability($job_profile_id);
        $data['disability_values'] = $this->Job_profile->disability_values($job_profile_id);
        $data['disability_eligibles'] = $this->Job_profile->get_disability_eligibles($job_profile_id);
        $data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
        $data['jp_factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);
        $data['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);

        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $job_profile->job_charge_ID
        ])->result();

        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        $this->load->view('admin/job_profile/job_profile_show', $data);
    }

    public function update_sts()
    {
        $job_profile_id = $this->input->post('job_profile_id');

        $job_profile = $this->Job_profile->find($job_profile_id);

        if ($this->Job_profile->is_allow_disability_eligible($job_profile_id) && 
            count($this->Job_profile->get_disability_eligibles($job_profile_id)) == 0) {
            echo json_encode([
                'error' => 'EL perfil laboral no puede ser activo, es necesario agregar discapacidades aptas para el puesto.'
            ]);
            return;
        }

        $trans_sts = $this->Job_profile->update_sts($job_profile_id);

        if ((!$job_profile->active) == 1 && $trans_sts) {

            $created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
            $recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';

            $array_subject = [
                $job_profile->job_title
            ];
    
            $subject_profile = join(" ", $array_subject);
        
            $data_view = [
                'body' => 'Perfil laboral ha sido activo',
                'job_profile' => $job_profile,
                'created_by' => $recruiter_name
            ];

            $users = $this->Employer->get_internal_by_profile_id($job_profile->company_id, [
                1, //Empleador
                2, //Solicitante
                4 //SSO
            ]);

            foreach ($users as $user) {
                $emails[] = $user->email;
            }

            if (!empty($emails)) {
                $mail_view = $this->load->view('email/job_profiles/notify_job_profile', $data_view, true);
        
                $config = $this->Email_drafts->email_configuration();
                $this->email->initialize($config);
                $this->email->clear(TRUE);
                $this->email->from(ADMIN_EMAIL, SITE_NAME);
                $this->email->to($emails);
                $this->email->subject('Perfil laboral Activo - ' . $subject_profile);
                $this->email->message($mail_view);     
                //Send email
                $this->email->send();
            }
        }
        
        echo json_encode(
            [
                'success' => $trans_sts,
                'sts' =>  $trans_sts ? !$job_profile->active : $job_profile->active 
            ]
        );
    }

    public function get_consultants()
    {
        $result = $this->Workflow_consultant->get_all($this->input->post('company_id'));

        echo json_encode([
            'MESSAGE' => 'OK',
            'CONSULTORA' => $result
        ]);
    }
    
    public function get_clients_company()
    {
        $company_id = $this->input->post('company_id');
        $no_cia = $this->input->post('no_cia');
        $uni_neg = $this->input->post('uni_neg');

        $result = $this->Workflow_client->get_all($company_id, $no_cia, $uni_neg);

        echo json_encode([
            'MESSAGE' => 'OK',
            'CLIENTE' => $result
        ]);
    }

    public function get_cost_centers()
    {
        $company_id = $this->input->post('company_id');
        $no_cia = $this->input->post('no_cia');
        $uni_neg = $this->input->post('uni_neg');
        $cod_clie = $this->input->post('cod_clie');

        $result = $this->Workflow_cost_center->get_all($company_id, $no_cia, $cod_clie, $uni_neg);

        echo json_encode([
            'MESSAGE' => 'OK',
            'CENTROCOSTO' => $result
        ]);
    }

    public function alert_emails()
    {   
        $ads_row = $this->ads;
        $title = 'Gestion alertas - ' . SITE_NAME;

        $alert_emails = $this->db->from('tbl_job_profile_alert_emails')
                        ->get()
                        ->result();
        $data = compact([
            'title',
            'ads_row',
            'alert_emails'
        ]);

        $this->load->view('admin/job_profile/alert_emails', $data);
    }

    public function save_alert_emails()
    {   
        $email = (array)$this->input->post('emails');

        $this->db->truncate('tbl_job_profile_alert_emails');

        foreach ($email as $email) {
            $email = trim($email);
            if (empty($email)) {
                continue;
            }

            $data = [
                'email' => $email 
            ];

            $this->db->insert('tbl_job_profile_alert_emails', $data);
        }

        $this->session->set_flashdata('save_action', true);
        
        redirect('admin/job_profiles/alert_emails');        
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

    private function notify_change_by_email(
		$job_profile_id,  
		$old_disability_grade
	)
	{
        $is_diff_section_disability_grade = $this->Job_profile->is_diff_section_disability_grade($job_profile_id, $old_disability_grade);

        if (!$is_diff_section_disability_grade) {
            //return;
        }

		$job_profile = $this->Job_profile->find($job_profile_id);
		$created_by = $this->Employer->find($job_profile->created_by_recruiter_ID);
		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Administrador';

		$array_subject = [
	    	$job_profile->client_company_name,
	    	$job_profile->consultant_name,
	    	$job_profile->business_unit_name,
	    	$job_profile->cost_center,
	    	$job_profile->job_title
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Perfil laboral ha sido modificado',
			'job_profile' => $job_profile,
			'created_by' => $recruiter_name,
			'edited_by' => 'Administrador'
		];

        $data_view['disability_values'] = $this->Job_profile->disability_values($job_profile_id);
        $data_view['factor_valuations'] = $this->Job_profile->get_factor_valuations($job_profile_id);
        $data_view['factor_total_score'] = $this->Job_profile->get_factor_total_score($job_profile_id);
        $data_view['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);
        $data_view['structure_salary'] = false;
        
		$disability_eligibles = $this->Job_profile->get_disability_eligibles($job_profile_id);

		if (count($disability_eligibles) > 0) {
			$data_view['disability_eligibles'] = $disability_eligibles;
		}

        $emails = [];
        $users = $this->Employer->get_internal_by_profile_id($job_profile->company_id, 4);

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
	    $this->email->subject('Perfil laboral - Editado - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
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

		$created_by = 'Administrador';

		$array_subject = [
	    	$job_profile->client_company_name,
	    	$job_profile->consultant_name,
	    	$job_profile->business_unit_name,
	    	$job_profile->cost_center,
	    	$job_profile->job_title,
			$created_by
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'Nuevo perfil laboral creado',
			'job_profile' => $job_profile,
			'created_by' => $created_by,
			'edited_by' => ''
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
	    $this->email->subject('Nuevo Perfil laboral - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();

        $users = $this->Employer->get_internal_by_profile_id($job_profile->company_id, 4);

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
	    $this->email->subject('Nuevo Perfil laboral - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
	}

    public function export()
    {
        $id = $this->input->get('id');

        if (!$id) {
            show_404();
        }

        if ($this->input->get('format') == 'excel') {
            $this->load->library(
                'Exports/Job_profile_detail_export', 
                null, 
                'Job_profile_detail_export'
            );
    
            $this->Job_profile_detail_export->build([
                'id' => $id,
                'resource' => $this->input->get('resource'),
                'disability' => $this->input->get('disability'),
                'valorization' => $this->input->get('valorization'),
                'salary_structure' => $this->input->get('salary_structure'),
            ]);
            $this->Job_profile_detail_export->download('pefil-laboral');
        }

        if ($this->input->get('format') == 'pdf') {

            $this->load->library(
                'Pdf/Job_profile_detail_pdf', 
                null, 
                'Job_profile_detail_pdf'
            );
    
            $this->Job_profile_detail_pdf->show([
                'id' => $id,
                'resource' => $this->input->get('resource'),
                'disability' => $this->input->get('disability'),
                'valorization' => $this->input->get('valorization'),
                'salary_structure' => $this->input->get('salary_structure')
            ]);

        }
    }

    public function export_excel_list()
    {
        $company_id = $this->input->get('company_id');
        $ids = explode(',', $this->input->get('ids'));
        $names = explode(',', $this->input->get('names'));

        if (!$company_id) {
            echo 'Debe seleccionar una compañia';
            return;
        }

        if (count($ids) == 0) {
            echo 'Debe seleccionar al menos 1 perfil';
            return;
        }   

        $this->load->library(
            'Exports/Job_profile_list_export', 
            null, 
            'Job_profile_list_export'
        );

        $this->Job_profile_list_export->build([
            'ids' => $ids,
            'company_id' => $company_id,
            'show_url_detail_pdf' => $this->input->get('show_detail_pdf'),
            'show_url_detail_excel' => $this->input->get('show_detail_excel')
        ]);

        if(!empty($this->input->get('show_detail_pdf'))) {
            $zip = new ZipArchive();

            $nombreArchivoZip = __DIR__ . "/reporte-perfil-laborales.zip";

            if (!$zip->open($nombreArchivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                exit("Error abriendo ZIP en $nombreArchivoZip");
            }

            for ($i=0; $i < count($ids); $i++) { 
                
                $this->load->library(
                    'Pdf/Job_profile_detail_pdf', 
                    null, 
                    'Job_profile_detail_pdf'
                );
            
                $this->Job_profile_detail_pdf->save([
                    'id' => $ids[$i],
                    'resource' => 1,
                    'disability' => 1,
                    'valorization' => 1,
                    'salary_structure' => 1
                ], "public/". $names[$i] .".pdf");

                $this->Job_profile_list_export->download($names[$i], true);
                $ruta_pdf =  FCPATH . "public/".$names[$i].".pdf";
                $ruta_excel =  FCPATH . "public/".$names[$i].".xlsx";
        
                $name_pdf = basename($ruta_pdf);
                $name_excel = basename($ruta_excel);
                $zip->addFile($ruta_pdf, $name_pdf);
                $zip->addFile($ruta_excel, $name_excel);

            }
    
                $resultado = $zip->close();
                if (!$resultado) {
                    exit("Error creando archivo");
                }

                $nombreAmigable = "reporte-mof.zip";
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-disposition: attachment; filename=$nombreAmigable");
                readfile($nombreArchivoZip);
           

               for ($i=0; $i < count($names); $i++) { 
                unlink(realpath(APPPATH . '../public/'. $names[$i] . '.pdf'));
                unlink(realpath(APPPATH . '../public/'. $names[$i] . '.xlsx'));
               }
                unlink($nombreArchivoZip);

               
        } else {
            $this->Job_profile_list_export->download('reporte-perfil-laborales', false);
        }
      
    }
}
