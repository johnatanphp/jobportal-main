<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mofs extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        $this->load->model('Internal_area');
        $this->load->model('Mof');
        $this->load->model('Job_charge');
        $this->load->model('Work_experience');
        $this->load->model('Occupational_category');
        $this->load->model('Company');
        $this->load->model('Risk_criteria');
        $this->load->model('Country');
    }
    
    public function index()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Listado de MOF creados - ' . SITE_NAME;

        $filters = [
            'query' => trim((string)$this->input->get('query', true)),
            'requested' => trim((string)$this->input->get('requested', true)),
            'status' => trim((string)$this->input->get('status', true)),
            'company_id' => trim((string)$this->input->get('company_id') != '' ? $this->input->get('company_id') : '1') 
        ];

        $data['filters'] = $filters;
        $data['companies'] = $this->Company->get_all_internal();
        
        $this->load->view('admin/mof/list', $data);
    }

    public function search()
    {
        $params = $this->input->get();

        $this->db->select([
            'mof.ID',
            'mof.code',
            'mof.job_title',
            'mof.active',
            'mof.requested',
            'GROUP_CONCAT(internal_area.area_name SEPARATOR ", ") AS  belonging_areas',
            'c.company_name'
        ]);

        $this->db->from('tbl_mofs mof');
        $this->db->join('tbl_companies c', 'c.ID=mof.company_id');
        $this->db->join('tbl_mof_belonging_areas mof_belonging_area', 'mof_belonging_area.mof_ID=mof.ID', 'left');
        $this->db->join('tbl_internal_areas internal_area', 'mof_belonging_area.belonging_area_ID=internal_area.ID', 'left');

        if (isset($params['company_id']) && $params['company_id']) {
            $this->db->where('mof.company_id', $params['company_id']);
        }

        if (isset($params['requested']) && $params['requested'] != '') {
            $this->db->where('mof.requested', $params['requested']);
        }

        if (isset($params['status']) && $params['status'] != '') {
            $this->db->where('mof.active', $params['status']);
        }
        
        $this->db->group_by('mof.ID');

        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function create()
    {
        $this->form_validation->set_rules('company_id', 'Empresa', 'trim|required');
        $this->form_validation->set_rules('last_update', 'Ultima actualización', 'trim|required|valid_date');
        $this->form_validation->set_rules('job_title', 'Nombre del cargo', 'trim|required|strip_all_tags'); 
        $this->form_validation->set_rules('belonging_areas[]', 'Áreas perteneciente', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('occupational_group', 'Grupo ocupacional', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('risk_criteria', 'Criterio de riesgo', 'trim|required|strip_all_tags');
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
            $data['title'] = 'Crear MOF - ' . SITE_NAME;
            $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => @$company->country_id]);
            $data['belonging_areas'] = $this->Internal_area->all(['active' => 1, 'country_id' => @$company->country_id]);
            $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => @$company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => @$company->country_id]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => @$company->country_id]);
            $data['occupational_categories'] = $this->Occupational_category->get_all(['active' => 1]); 
            $data['results_disability'] = $this->Mof->get_results_disability();
            $data['mof_factor_valuations'] = $this->Mof->get_factor_valuations();
            $data['mof_benefits'] = $this->Mof->get_benefits($company_id);
            $data['companies'] = $this->Company->get_all_internal();
            $data['country'] = $this->Country->find(@$company->country_id);
            $data['company_id'] = $company_id;
            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];          

            $this->load->view('admin/mof/create', $data);
            return;
        }

        $all_inputs = $this->input->post();

        $trans_id = $this->Mof->create($all_inputs);
        
        if ($trans_id !== false) {
            $this->session->set_flashdata('added_action', true);

            $this->load->library('Email/Mof/Mof_change_resources_email', null ,'Mof_change_resources_email');
			$this->Mof_change_resources_email->send($trans_id);

            $this->notify_by_email($trans_id);
        }

        redirect('admin/mofs/show/' . $trans_id);
    }

    public function edit($mof_id)
    {
        $mof = $this->Mof->find($mof_id);

        if (!$mof) {
            show_404();
        }

        if (!$mof->code) {
            $this->form_validation->set_rules(
                'code', 
                'Código del MOF', 
                'trim|required|edit_is_unique[tbl_mofs.code.' . $mof->ID . ']|strip_all_tags', 
                [
                    'edit_is_unique' => 'Código del MOF ya existe'
                ]
            );
        }

        $this->form_validation->set_rules('job_title', 'Nombre del cargo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('occupational_group', 'Grupo ocupacional', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('belonging_areas[]', 'Área perteneciente', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('risk_criteria', 'Criterio de riesgo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('education', 'Educación', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('study_grade_req', 'Grado de estudio deseable', 'trim|required');
        $this->form_validation->set_rules('education_req_detail', 'Más detalle', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('study_grade_min', 'Grado de estudio mínimo', 'trim|required');
        $this->form_validation->set_rules('education_min_detail', 'Descripción del empleo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('experience', 'Experiencia requerida', 'trim|required');
        $this->form_validation->set_rules('experience_detail', 'Más detalle', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('responsibilities[]', 'Responsabilidades', 'trim|strip_all_tags');
            
        $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

        $company = $this->Company->find($mof->company_id);

        if ($this->form_validation->run() === FALSE) {

            $data['ads_row'] = $this->ads;
            $data['title'] = 'Editar MOF - ' . SITE_NAME;
            $data['mof'] = $mof;
            $data['country'] = $this->Country->find($company->country_id);

            $data['mof_skills'] = $this->Mof->get_skills_by_mof_id($mof_id);
            $data['mof_responsibilities'] = $this->Mof->get_responsibilities_by_mof_id($mof_id);
            $data['mof_indicators'] = $this->Mof->get_indicators_by_mof_id($mof_id);
            $data['mof_belonging_areas'] = $this->Mof->get_belonging_areas_by_mof_id($mof_id);
            $data['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);

            $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
            $data['belonging_areas'] = $this->Internal_area->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
            $data['work_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
            $data['risk_criteria'] = $this->Risk_criteria->all(['active' => 1, 'country_id' => $company->country_id]);
                            
            $data['results_disability'] = $this->Mof->get_results_disability($mof_id);
            $data['mof_factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
            $data['occupational_categories'] = $this->Occupational_category->get_all(['active' => 1]);

            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];

            $this->load->view('admin/mof/edit', $data);
            return;
        }

        $all_inputs = $this->input->post();

        $old_resources = $this->Mof->get_resources($mof_id);
        $old_disability_grade = $this->Mof->disability_values($mof_id);

        $trans_status = $this->Mof->edit(
            $all_inputs,
            $mof_id
        );

        if ($trans_status) {
            $this->session->set_flashdata('update_action', true);

            if (count($this->Mof->get_change_resources($mof_id, $old_resources)) > 0) {

                //Actualizar datos
                $this->Mof->update($mof_id, ['occupational_exams_approved' => 0, 'active' => 0]);
			
                //Cargar Email de notificación recursos
                $this->load->library('Email/Mof/Mof_change_resources_email', null ,'Mof_change_resources_email');
				$this->Mof_change_resources_email->send($mof_id);
			}

            $this->notify_change_by_email($mof_id, $old_disability_grade);
        }

        redirect('admin/mofs/show/' . $mof_id);
    }

    public function show($mof_id)
    {
        $mof = $this->Mof->find($mof_id);

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
        $data['results_disability'] = $this->Mof->get_results_disability($mof_id);
        $data['disability_values'] = $this->Mof->disability_values($mof_id);
        $data['disability_eligibles'] = $this->Mof->get_disability_eligibles($mof_id);
        $data['mof_factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
        $data['mof_factor_total_score'] = $this->Mof->get_factor_total_score($mof_id);
        $data['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);

        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $mof->job_charge_id
        ])->result();
        
        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        $this->load->view('admin/mof/show', $data);
    }

    public function update_sts()
    {
        $mof_id = $this->input->post('mof_id');

        $mof = $this->Mof->find($mof_id);

        if ($this->Mof->is_allow_disability_eligible($mof_id) && 
            count($this->Mof->get_disability_eligibles($mof_id)) == 0) {
            echo json_encode([
                'error' => 'EL MOF no puede ser activo, es necesario agregar discapacidades aptas para el puesto.'
            ]);
            return;
        }

        $trans_sts = $this->Mof->update_sts($mof_id);

        if ((!$mof->active) == 1 && $trans_sts) {

            $created_by = $this->Employer->find($mof->created_by_user_id);
            $recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';

            $array_subject = [
                $mof->job_title
            ];
    
            $subject_profile = join(" ", $array_subject);
        
            $data_view = [
                'body' => 'MOF ha sido activo',
                'mof' => $mof,
                'created_by' => $recruiter_name
                //'disability_values' => $this->Mof->disability_values($mof_id),
                //'disability_eligibles' => $this->Mof->get_disability_eligibles($mof_id),
                //'factor_valuations' => $this->Mof->get_factor_valuations($mof_id),
                //'factor_total_score' => $this->Mof->get_factor_total_score($mof_id),
                //'structure_salary' => true
            ];

            $users = $this->Employer->get_internal_by_profile_id($mof->company_id, [
                1, //Empleador
                2, //Solicitante
                4  //SSO
            ]);

           foreach ($users as $user) {
                $emails[] = $user->email;
            }

            if (!empty($emails)) {
                $mail_view = $this->load->view('email/mof/notify_mof', $data_view, true);
        
                $config = $this->Email_drafts->email_configuration();
                $this->email->initialize($config);
                $this->email->clear(TRUE);
                $this->email->from(ADMIN_EMAIL, SITE_NAME);
                $this->email->to($emails);
                $this->email->subject('MOF Activo - ' . $subject_profile);
                $this->email->message($mail_view);     
                //Send email
                $this->email->send();
            }
        }

        echo json_encode([
            'success' => $trans_sts,
            'sts' => $trans_sts ? !$mof->active : $mof->active 
        ]);
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

    public function alert_emails()
    {   
        $ads_row = $this->ads;
        $title = 'Gestion alertas - ' . SITE_NAME;

        $alert_emails = $this->db->from('tbl_mof_alert_emails')
                        ->get()
                        ->result();
        $data = compact([
            'title',
            'ads_row',
            'alert_emails'
        ]);

        $this->load->view('admin/mof/alert_emails', $data);
    }

    public function save_alert_emails()
    {   
        $email = (array)$this->input->post('emails');

        $this->db->truncate('tbl_mof_alert_emails');

        foreach ($email as $email) {
            $email = trim($email);
            if (empty($email)) {
                continue;
            }

            $data = [
                'email' => $email 
            ];

            $this->db->insert('tbl_mof_alert_emails', $data);
        }

        $this->session->set_flashdata('save_action', true);
        
        redirect('admin/mofs/alert_emails');        
    }
    
    private function notify_change_by_email(
		$mof_id,
		$old_disability_grade
    )
    {
        $is_diff_section_disability_grade = $this->Mof->is_diff_section_disability_grade($mof_id, $old_disability_grade);

        if (!$is_diff_section_disability_grade) {
          //  return;
        }

		$mof = $this->Mof->find($mof_id);
		$created_by = $this->Employer->find($mof->created_by_user_id);

		$recruiter_name = $created_by ? $created_by->first_name . ' ' . $created_by->last_name : '';

	    $array_subject = [
	    	$mof->job_title,
	    	$recruiter_name
	    ];

	    $subject_profile = join(" ", $array_subject);

		$data_view = [
			'body' => 'MOF ha sido modificado',
			'mof' => $mof,
			'created_by' => $recruiter_name
		];

        $data_view['disability_values'] = $this->Mof->disability_values($mof_id);
        $data_view['factor_valuations'] = $this->Mof->get_factor_valuations($mof_id);
        $data_view['factor_total_score'] = $this->Mof->get_factor_total_score($mof_id);
        $data_view['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $mof_id);
        $data_view['structure_salary'] = false;
        
		$disability_eligibles = $this->Mof->get_disability_eligibles($mof_id);

		if (count($disability_eligibles) > 0) {
			$data_view['disability_eligibles'] = $disability_eligibles;
		}

		$emails = [];
		
        $users = $this->Employer->get_internal_by_profile_id($mof->company_id, 4);

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
	    $this->email->subject('MOF - Edición - ' . $subject_profile);
	    $this->email->message($mail_view);     
	    //Send email
	    $this->email->send();
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
			'body' => 'Nuevo MOF creado',
			'mof' => $mof,
			'created_by' => 'Administrador'
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
		$this->email->subject('MOF creado - ' . $subject_profile);
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
                'Exports/Mof_detail_export', 
                null, 
                'Mof_detail_export'
            );
    
            $this->Mof_detail_export->build([
                'id' => $id,
                'resource' => $this->input->get('resource'),
                'disability' => $this->input->get('disability'),
                'valorization' => $this->input->get('valorization'),
                'salary_structure' => $this->input->get('salary_structure'),
            ]);
            $this->Mof_detail_export->download('mof');
        }

        if ($this->input->get('format') == 'pdf') {

            $this->load->library(
                'Pdf/Mof_detail_pdf', 
                null, 
                'Mof_detail_pdf'
            );
        
            $this->Mof_detail_pdf->show([
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
            'Exports/Mof_list_export', 
            null, 
            'Mof_list_export'
        );

        $this->Mof_list_export->build([
            'ids' => $ids,
            'company_id' => $company_id,
            'show_url_detail_pdf' => $this->input->get('show_detail_pdf'),
            'show_url_detail_excel' => $this->input->get('show_detail_excel')
        ]);


        if(!empty($this->input->get('show_detail_pdf'))) {
            $zip = new ZipArchive();

            $nombreArchivoZip = __DIR__ . "/reporte-mof.zip";

            if (!$zip->open($nombreArchivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                exit("Error abriendo ZIP en $nombreArchivoZip");
            }

            for ($i=0; $i < count($ids); $i++) { 
                
                $this->load->library(
                    'Pdf/Mof_detail_pdf', 
                    null, 
                    'Mof_detail_pdf'
                );
            
                $this->Mof_detail_pdf->save([
                    'id' => $ids[$i],
                    'resource' => 1,
                    'disability' => 1,
                    'valorization' => 1,
                    'salary_structure' => 1
                ], "public/". $names[$i] .".pdf");

                $this->Mof_list_export->download($names[$i], true);
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
            $this->Mof_list_export->download('reporte-mof', false);
        }
        

    }
        
}
