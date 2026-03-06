<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_layouts extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Job_layout');
        $this->load->model('Job_charge');
        $this->load->model('Work_experience');
        $this->load->model('Occupational_category');
        $this->load->model('Company');
        $this->load->model('Sunat_code');
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
        $data['title'] = 'Listado Layouts de puesto creados - ' . SITE_NAME;

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

        $this->load->view('admin/job_layouts/job_layout_list', $data);
    }

    public function search()
    {
        $params = $this->input->get();

        $this->db->select([
            'job_layout.*',
            'c.company_name'
        ]);
        $this->db->from('tbl_job_layouts job_layout');
        $this->db->join('tbl_companies c', 'c.ID=job_layout.company_id');

        if ($params['company_id']) {
            $this->db->where('job_layout.company_id', $params['company_id']);
        }

        if (isset($params['requested']) && $params['requested'] != '') {
            $this->db->where('requested', $params['requested']);
        }

        if (isset($params['status']) && $params['status'] != '') {
            $this->db->where('active', $params['status']);
        }

        $this->db->order_by('job_layout.active', 'DESC');
        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function create()
    {
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
            $data['title'] = 'Crear layout de puesto - ' . SITE_NAME;
            $data['benefits'] = $this->Job_layout->get_benefits($company_id);
            $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => @$company->country_id]);
            $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => @$company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => @$company->country_id]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => @$company->country_id]);
            $data['occupational_categories'] = $this->Occupational_category->get_all(['active' => 1]);
            $data['companies'] = $this->Company->get_all_internal();
            $data['sunat_codes'] = $this->Sunat_code->get_all_sunat_code();
            $data['country'] = $this->Country->find(@$company->country_id);
            $data['company_id'] = $company_id;
            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];
            $data['factor_valuations'] = $this->Job_layout->get_factor_valuations();
            $data['disability_options'] = $this->Job_layout->get_results_disability_options();

            $this->load->view('admin/job_layouts/create', $data);
            return;
        }

        // // 🔒 VALIDACIÓN DE DUPLICADO
        // $job_title  = trim($this->input->post('job_title'));

        // $exists = $this->db
        //     ->where('job_title', $job_title)
        //     ->where('company_id', $company_id)
        //     ->count_all_results('tbl_job_layouts') > 0;

        // if ($exists) {
        //     $this->session->set_flashdata(
        //         'error',
        //         'No se puede crear el layout: el cargo ya existe en esta empresa.'
        //     );
        //     redirect('admin/job_layouts/create?company_id=' . $company_id);
        //     return;
        // }

        $all_inputs = $this->input->post();
        $trans_id = $this->Job_layout->create(
            $all_inputs
        );

        if ($trans_id !== false) {
            $this->session->set_flashdata('added_action', true);
                        
            //Notificar creacion por email
            $this->notify_by_email($trans_id);

            // //Notificar revision de recursos del layout de puesto por email
            // $this->load->library(
            //     'Email/Job_layout/Job_layout_change_resources_email', 
            //     null, 
            //     'Job_layout_change_resources_email'
            // );
			// $this->Job_layout_change_resources_email->send($trans_id);
        }

        redirect('admin/job_layouts/show/' . $trans_id);
    }

    public function edit($job_layout_id)
    {
        $job_layout = $this->Job_layout->find($job_layout_id);
    
        if (!$job_layout) {
            show_404();
        }

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

        $company_id = $job_layout->company_id;
        $company = $this->Company->find($company_id);

        if ($this->form_validation->run() === FALSE) {

            $data['ads_row'] = $this->ads;
            $data['title'] = 'Editar layout de puesto - ' . SITE_NAME;
            $data['job_layout'] = $job_layout;
            $data['country'] = $this->Country->find($company->country_id);
            $data['skills'] = $this->Job_layout->get_skills_by_job_layout_id($job_layout_id);
            $data['responsibilities'] = $this->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
            $data['benefits'] = $this->Job_layout->get_benefits($company_id, $job_layout_id);
            $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
            $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['company'] = $company;
            $data['occupational_categories'] = $this->Occupational_category->get_all(['active' => 1]);
            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

            $data['disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
            $data['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
            $data['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
            $data['sunat_codes'] = $this->Sunat_code->get_all_sunat_code();

            $this->load->view('admin/job_layouts/edit', $data); 
            return;
        }

        $all_inputs = $this->input->post();

        $old_resources = $this->Job_layout->get_resources($job_layout_id);
        $old_disability_values = $this->Job_layout->disability_values($job_layout_id);

        /////
        // $job_title = trim($all_inputs['job_title']);
        // $company_id = $job_layout->company_id;

        // $exists = $this->db
        //     ->where('job_title', $job_title)
        //     ->where('company_id', $company_id)
        //     ->where('id !=', $job_layout_id)
        //     ->limit(1)
        //     ->get('tbl_job_layouts')
        //     ->num_rows();

        // if ($exists > 0) {
        //     $this->session->set_flashdata(
        //         'error',
        //         'El cargo ya existe en esta empresa.'
        //     );
        //     redirect('admin/job_layouts/edit/' . $job_layout_id);
        //     return;
        // }
        ///

        $trans_status = $this->Job_layout->edit(
            $all_inputs,
            $job_layout_id
        );

        if ($trans_status) {
            $this->session->set_flashdata('update_action', true);

            //Notificar actualizacion
            $this->notify_change_by_email($job_layout_id, $old_disability_values);

            // if (count($this->Job_layout->get_change_resources($job_layout_id, $old_resources)) > 0) {

			// 	//Actualizar datos
			// 	$this->Job_layout->update($job_layout_id, ['occupational_exams_approved' => 0, 'active' => 0]);

			// 	//Notificar registro recurso
			// 	$this->load->library('Email/Job_layout/Job_layout_change_resources_email', null ,'Job_layout_change_resources_email');
			// 	$this->Job_layout_change_resources_email->send($job_layout_id);
			// }
        }

        redirect('admin/job_layouts/show/' . $job_layout_id);
    }

    public function show($job_layout_id)
    {
        $job_layout = $this->Job_layout->find($job_layout_id);

        if (!$job_layout) {
            show_404();
        }

        $company = $this->Company->find($job_layout->company_id);
      
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Mostrar layouts de puesto - ' . SITE_NAME;
        $data['job_layout'] = $job_layout;
        $data['country'] = $this->Country->find($company->country_id);
        $data['skills'] = $this->Job_layout->get_skills_by_job_layout_id($job_layout_id);
        $data['responsibilities'] = $this->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
        $data['benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $job_layout->job_charge_id
        ])->result();
        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];
        $data['disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
        $data['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
        $data['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
        //$data['results_disability'] = $this->Job_layout->get_results_disability($job_layout_id);
        //$data['disability_values'] = $this->Job_layout->disability_values($job_layout_id);
        //$data['disability_eligibles'] = $this->Job_layout->get_disability_eligibles($job_layout_id);
        $data['sunat_code'] = $this->Sunat_code->get_sunat_code_by_code($job_layout->sunat_code);

        $this->load->view('admin/job_layouts/job_layout_show', $data);
    }

    public function update_sts()
    {
        $job_layout_id = $this->input->post('id');

        $job_layout = $this->Job_layout->find($job_layout_id);

        // if ($this->Job_layout->is_allow_disability_eligible($job_layout_id) && 
        //     count($this->Job_layout->get_disability_eligibles($job_layout_id)) == 0) {
        //     echo json_encode([
        //         'error' => 'EL layout de puesto no puede ser activo, es necesario agregar discapacidades aptas para el puesto.'
        //     ]);
        //     return;
        // }

        $trans_sts = $this->Job_layout->update_sts($job_layout_id);

        if ((!$job_layout->active) == 1 && $trans_sts) {

            $created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		    $recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

            $array_subject = [
                $job_layout->job_title
            ];
    
            $subject = join(" ", $array_subject);
        
            $data_view = [
                'body' => 'Layout de puesto ha sido activo',
                'job_layout' => $job_layout,
                'created_by' => $recruiter_name
            ];

            $users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, [
                1, //Empleador
                2, //Solicitante
                4  //SSO
            ]);

            foreach ($users as $user) {
                $emails[] = $user->email;
            }

            if (!empty($emails)) {
                $mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);
        
                $config = $this->Email_drafts->email_configuration();
                $this->email->initialize($config);
                $this->email->clear(TRUE);
                $this->email->from(ADMIN_EMAIL, SITE_NAME);
                $this->email->to($emails);
                $this->email->subject('Layout de puesto Activo - ' . $subject);
                $this->email->message($mail_view);     
                //Send email
                $this->email->send();
            }
        }
        
        echo json_encode([
            'success' => $trans_sts,
            'sts' =>  $trans_sts ? !$job_layout->active : $job_layout->active 
        ]);
    }

    public function alert_emails()
    {   
        $ads_row = $this->ads;
        $title = 'Gestion alertas - ' . SITE_NAME;

        $alert_emails = $this->db->from('tbl_job_layout_alert_emails')
                        ->get()
                        ->result();
        $data = compact([
            'title',
            'ads_row',
            'alert_emails'
        ]);

        $this->load->view('admin/job_layouts/alert_emails', $data);
    }

    public function save_alert_emails()
    {   
        $email = (array)$this->input->post('emails');

        $this->db->truncate('tbl_job_layout_alert_emails');

        foreach ($email as $email) {
            $email = trim($email);
            if (empty($email)) {
                continue;
            }

            $data = [
                'email' => $email 
            ];

            $this->db->insert('tbl_job_layout_alert_emails', $data);
        }

        $this->session->set_flashdata('save_action', true);
        
        redirect('admin/job_layouts/alert_emails');        
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
		$job_layout_id,  
		$old_disability_grade
	)
	{
        $is_diff_section_disability_grade = $this->Job_layout->is_diff_section_disability_grade($job_layout_id, $old_disability_grade);

        if (!$is_diff_section_disability_grade) {
            //return;
        }

		$job_layout = $this->Job_layout->find($job_layout_id);
		$created_by = $this->Employer->find($job_layout->created_by_recruiter_id);
		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : 'Portal Administrador';

		$array_subject = [
	    	$job_layout->job_title
	    ];

	    $subject = join(" ", $array_subject);

		$data_view = [
			'body' => 'Layout de puesto ha sido modificado',
			'job_layout' => $job_layout,
			'created_by' => $recruiter_name,
			'edited_by' => 'Portal Administrador'
		];

        //$data_view['disability_values'] = $this->Job_layout->disability_values($job_layout_id);
        $data_view['disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
        $data_view['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
        $data_view['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
        $data_view['benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
        $data_view['structure_salary'] = false;
        
		$disability_eligibles = $this->Job_layout->get_disability_eligibles($job_layout_id);

		if (count($disability_eligibles) > 0) {
			$data_view['disability_eligibles'] = $disability_eligibles;
		}

        $emails = [];
        $users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);

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
	    $this->email->subject('Layout de puesto - Editado - ' . $subject);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
	}

    public function validate_job_title_admin()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $job_title  = trim($this->input->post('job_title'));
        $company_id = $this->input->post('company_id');
        $id         = $this->input->post('id'); // opcional (para edición)

        if ($job_title === '' || empty($company_id)) {
            echo json_encode(['existe' => false]);
            return;
        }

        $this->db->where('job_title', $job_title);
        $this->db->where('company_id', $company_id);

        // Excluir el mismo registro en edición
        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }

        $existe = $this->db->count_all_results('tbl_job_layouts') > 0;

        echo json_encode([
            'existe' => $existe
        ]);
    }

    public function validate_job_title_edit_admin()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $job_title  = trim($this->input->post('job_title'));
        $id         = (int) $this->input->post('id');
        $company_id = (int) $this->input->post('company_id');

        $this->db->where('job_title', $job_title);
        $this->db->where('company_id', $company_id);
        $this->db->where('id !=', $id);

        $existe = $this->db->count_all_results('tbl_job_layouts') > 0;

        echo json_encode(['existe' => $existe]);
    }




    private function notify_by_email($job_layout_id)
	{
        $job_layout = $this->Job_layout->find($job_layout_id);
        $created_by = null;
        
		$array_subject = [
	    	$job_layout->job_title,
	    ];

	    $subject = join(" ", $array_subject);

		$data_view = [
			'body' => 'Nuevo Layout de puesto creado',
			'job_layout' => $job_layout,
			'created_by' => 'Portal Administrador',
		];

        //$data_view['disability_values'] = $this->Job_layout->disability_values($job_layout_id);
        $data_view['disability_options'] = $this->Job_layout->get_results_disability_options($job_layout_id);
        $data_view['factor_valuations'] = $this->Job_layout->get_factor_valuations($job_layout_id);
        $data_view['factor_total_score'] = $this->Job_layout->get_factor_total_score($job_layout_id);
        $data_view['benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);
		$data_view['structure_salary'] = true;

        //Notifcar a correos que esten como alertas
    	$result_emails = $this->db->from('tbl_job_layout_alert_emails')
                 ->where('company_id', $job_layout->company_id)
    	         ->get()
    	         ->result();

    	$alert_emails = [];

    	foreach ($result_emails as $row) {
    		$alert_emails[] = $row->email;
    	}

        if (!empty($alert_emails)) {
            
            $mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($alert_emails);
            $this->email->subject('Nuevo Layout de puesto - ' . $subject);
            $this->email->message($mail_view);     
            //Send email
            $this->email->send();
        }

        //Notificar a SSO
        $sso_emails = [];

        $users = $this->Employer->get_internal_by_profile_id($job_layout->company_id, 4);

		foreach ($users as $user) {
			$sso_emails[] = $user->email;
		}

        if (!empty($sso_emails)) {
            $data_view['structure_salary'] = false;
            $mail_view = load_email_view('email/job_layouts/notify_job_layout', $data_view);
        
            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($sso_emails);
            $this->email->subject('Nuevo Layout de puesto - ' . $subject);
            $this->email->message($mail_view);     
            //Send email
            $this->email->send();
        }
	}

    public function export()
    {
        $id = $this->input->get('id');

        if (!$id) {
            show_404();
        }

        if ($this->input->get('format') == 'excel') {
            $this->load->library(
                'Exports/Job_layout_detail_export', 
                null, 
                'Job_layout_detail_export'
            );
    
            $this->Job_layout_detail_export->build([
                'id' => $id,
                'disability' => $this->input->get('disability'),
                'valorization' => $this->input->get('valorization'),
                'salary_structure' => $this->input->get('salary_structure'),
            ]);
            $this->Job_layout_detail_export->download('layout-de-puesto');
        }

        if ($this->input->get('format') == 'pdf') {

            $this->load->library(
                'Pdf/Job_layout_detail_pdf', 
                null, 
                'Job_layout_detail_pdf'
            );
    
            $this->Job_layout_detail_pdf->show([
                'id' => $id,
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
            echo 'Debe seleccionar al menos 1 registro';
            return;
        }   

        $this->load->library(
            'Exports/Job_layout_list_export', 
            null, 
            'Job_layout_list_export'
        );

        $this->Job_layout_list_export->build([
            'ids' => $ids,
            'company_id' => $company_id,
            'show_url_detail_pdf' => $this->input->get('show_detail_pdf'),
            'show_url_detail_excel' => $this->input->get('show_detail_excel')
        ]);

        if (!empty($this->input->get('show_detail_pdf'))) {
            $zip = new ZipArchive();

            $zip_file_name = __DIR__ . "/reporte-layout-de-puesto.zip";

            if (!$zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                exit("Error abriendo ZIP en $zip_file_name");
            }

            for ($i = 0; $i < count($ids); $i++) { 
                
                $this->load->library(
                    'Pdf/Job_layout_detail_pdf', 
                    null, 
                    'Job_layout_detail_pdf'
                );
            
                $this->Job_layout_detail_pdf->save([
                    'id' => $ids[$i],
                    'resource' => 1,
                    'disability' => 1,
                    'valorization' => 1,
                    'salary_structure' => 1
                ], "public/". $names[$i] .".pdf");

                $path_pdf =  FCPATH . "public/".$names[$i].".pdf";
                $name_pdf = basename($path_pdf);
                $zip->addFile($path_pdf, $name_pdf);
            }

            $path_excel =  FCPATH . "public/layouts-de-puestos.xlsx";
            $name_excel = basename($path_excel);
            $this->Job_layout_list_export->save($path_excel, true);
            $zip->addFile($path_excel, $name_excel);
    
            $zip_result = $zip->close();
            if (!$zip_result) {
                exit("Error creando archivo");
            }

            $file_name = "reporte-layout-de-puesto.zip";
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=$file_name");
            readfile($zip_file_name);
        
            for ($i = 0; $i < count($names); $i++) { 
                unlink(realpath(APPPATH . '../public/'. $names[$i] . '.pdf'));
                unlink(realpath(APPPATH . '../public/'. $names[$i] . '.xlsx'));
            }
            unlink($zip_file_name);
            unlink($path_excel);

        } else {
            $this->Job_layout_list_export->download('reporte-layout-de-puesto', false);
        } 
    }
}
