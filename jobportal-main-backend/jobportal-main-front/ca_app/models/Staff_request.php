<?php
class Staff_request extends CI_Model 
{ 
    public function get_staff_request_by_id($request_id)
    {   
        $this->db->select([
            'staff_request.*',
            'app_user_recruiters.email AS recruiter_email',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'employer.ID AS employer_ID',
            'employer.email AS employer_email',
            'employer.first_name AS employer_first_name',
            'request_canceled.canceled_at AS request_canceled_at',
            'request_canceled.reason AS reason_cancellation'
        ]);

        $this->db->from('tbl_staff_requests staff_request');
        $this->db->join('tbl_staff_request_canceled request_canceled', 'request_canceled.request_id=staff_request.ID', 'left');
        $this->db->join('tbl_employers app_user_recruiters', 'staff_request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_employers employer', 'staff_request.employer_ID=employer.ID', 'left');
        
        $this->db->where('staff_request.ID', $request_id);

        return $this->db->get()->row();
    }

    public function find($id)
    {
        $this->db->from('tbl_staff_requests');
        $this->db->where('ID', $id);
        
        return $this->db->get()->row();
    }

    public function create_external_staff_request($data)
    {
    	$this->db->trans_start();
       
        //Crear solicitud
    	$this->db->insert('tbl_staff_requests', $data['data_staff_request']);

    	$request_id = $this->db->insert_id();

        $data_staff_request = $data['data_staff_request'];
  
        //Registrar horarios de trabajos
        $this->add_working_hours($data['data_working_hours'], $request_id);

    	//Registrar beneficios laborales
    	$this->add_additional_benefits($data['data_additional_benefits'], $request_id);

        //Registrar recursos
        $this->add_resources($data['data_resources'], $request_id);

		//rRegistrar conocimientos informaticos requeridos para el puesto
		$this->add_computing($data['data_computing'], $request_id);

		//Regisrar languajes requeridos para el puesto
		$this->add_languages($data['data_languages'], $request_id);
		
		//Registrar funciones requeridos para el puesto
		$this->add_job_functions($data['data_job_functions'], $request_id);

        //Registrar competencias laborales
        $charge_id = $data_staff_request['charge_ID'];
        $this->add_fixed_competences($charge_id, $request_id);

        //Registrar competencias laborales de la solicitud
        $this->add_additional_competences($data['data_additional_competences'], $request_id);
	
		$this->db->trans_complete();

		return $this->db->trans_status() ? $request_id : FALSE;
    }

    public function edit_external_staff_request($data, $request_id)
    {
        $this->db->trans_start();
        
        $data_staff_request = $data['data_staff_request'];

        //Editar solicitud
        $this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', $data_staff_request);

        //Editar recursos
        $this->db->where('request_id', $request_id);
        $this->db->update('tbl_staff_request_resources', $data['data_resources']);

        //Registrar horarios de trabajos
        $this->add_working_hours($data['data_working_hours'], $request_id);

        //Registrar beneficios laborales
        $this->add_additional_benefits($data['data_additional_benefits'], $request_id);

        //rRegistrar conocimientos informaticos requeridos para el puesto
        $this->add_computing($data['data_computing'], $request_id);

        //Regisrar languajes requeridos para el puesto
        $this->add_languages($data['data_languages'], $request_id);
        
        //Registrar funciones requeridos para el puesto
        $this->add_job_functions($data['data_job_functions'], $request_id);

        //Registrar competencias laborales
        $charge_id = $data_staff_request['charge_ID'];
        $this->add_fixed_competences($charge_id, $request_id);

        //Registrar competencias laborales de la solicitud
        $this->add_additional_competences($data['data_additional_competences'], $request_id);
    
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
    
    public function create_internal_staff_request($data)
    {
        $this->db->trans_start();
        //Crear solicitud
        $this->db->insert('tbl_staff_requests', $data['data_staff_request']);

        $request_id = $this->db->insert_id();

        //Registrar horarios de trabajos
        $this->add_working_hours($data['data_working_hours'], $request_id);

        //Regisrar beneficios laborales
        $this->add_additional_benefits($data['data_additional_benefits'], $request_id);
        
        //Registrar recursos
        $this->add_resources($data['data_resources'], $request_id);

        //Registrar personal para la autorización de la solicitud
        $this->Staff_request_authoritation->create_request_for_authorizations(
            $data['data_authorities'], 
            $request_id
        );
        
        $this->db->trans_complete();

        return $this->db->trans_status() ? $request_id : FALSE;
    }

    public function edit_internal_staff_request($data, $request_id)
    {
        $this->db->trans_start();

        //Actualizar solicitud
        $this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', $data['data_staff_request']);

        //Actualizar horarios de trabajos
        $this->add_working_hours($data['data_working_hours'], $request_id);

        //Actualizar beneficios laborales
        $this->add_additional_benefits($data['data_additional_benefits'], $request_id);
        
        //Registrar personal para la autorización de la solicitud
        $this->Staff_request_authoritation->create_request_for_authorizations(
            $data['data_authorities'], 
            $request_id
        );

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_external_staff_request_by_id($request_id)
    {   
        $this->db->select(
            array(
                'staff_request.*',
                'app_user_recruiters.first_name AS recruiter_first_name',
                'employer.ID AS employer_ID',
                'employer.email AS employer_email',
                'employer.first_name AS employer_first_name',
                'posted_job.ID AS job_ID',
                'wf_area.name wf_area_name',
                'request_canceled.canceled_at AS request_canceled_at',
                'request_canceled.reason AS reason_cancellation'
            )
        );

        $this->db->from('tbl_staff_requests staff_request');
        $this->db->join('tbl_staff_request_canceled request_canceled', 'request_canceled.request_id=staff_request.ID', 'left');
        $this->db->join('tbl_post_jobs posted_job', 'staff_request.ID=posted_job.request_ID', 'left');
        $this->db->join('tbl_employers app_user_recruiters', 'staff_request.recruiter_ID=app_user_recruiters.ID', 'left');
        $this->db->join('tbl_employers employer', 'staff_request.employer_ID=employer.ID', 'left');
        $this->db->join('tbl_workflow_areas wf_area', 'staff_request.wf_area_code=wf_area.code AND staff_request.no_cia=wf_area.cia_code', 'left');
        
        $this->db->where('staff_request.ID', $request_id);

        return $this->db->get()->row();
    }

    public function get_internal_staff_request_by_id($request_id)
    {   
        $this->db->select([
            'staff_request.*',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'employer.ID AS employer_ID',
            'employer.email AS employer_email',
            'employer.first_name AS employer_first_name',
            'posted_job.ID AS job_ID',
            'request_canceled.canceled_at AS request_canceled_at',
            'request_canceled.reason AS reason_cancellation'
        ]);

        $this->db->from('tbl_staff_requests staff_request');
        $this->db->join('tbl_staff_request_canceled request_canceled', 'request_canceled.request_id=staff_request.ID', 'left');
        $this->db->join('tbl_post_jobs posted_job', 'staff_request.ID=posted_job.request_ID', 'left');
        $this->db->join('tbl_employers app_user_recruiters', 'staff_request.recruiter_ID=app_user_recruiters.ID', 'left');
        $this->db->join('tbl_employers employer', 'staff_request.employer_ID=employer.ID', 'left');
        
        $this->db->where('staff_request.ID', $request_id);

        return $this->db->get()->row();        
    }
    
    public function get_job_functions_by_request_id($request_id)
    {
		$this->db->from('tbl_staff_request_job_functions job_functions');
		$this->db->where('job_functions.request_ID', $request_id);

		return $this->db->get()->result();
    }

    public function get_languages_by_request_id($request_id)
    {    	
		$this->db->from('tbl_staff_request_languages languages');
		$this->db->where('languages.request_ID', $request_id);

		return $this->db->get()->result();
    }

    public function get_computing_applications_by_request_id($request_id)
    {
    	$this->db->from('tbl_staff_request_computing computing');
   		$this->db->where('computing.request_ID', $request_id);
	
		return $this->db->get()->result();
    }
    
    public function validate_filter_search_staff_request($filter)
    {
        if (!in_array($filter['status_rq'], array('unassigned', 'assigned', 'published', 'pending', 'rejected', 'canceled', 'all'))) {
            $filter['status_rq'] = 'all';
        }

        if (!isset($filter['status_rs'])) {
            $filter['status_rs'] = 'all';
        }
        
        if (!in_array($filter['type'], array('internal', 'external', 'all'))) {
            $filter['type'] = 'all';
        }

        if (!isset($filter['request_year']) || (int)$filter['request_year'] > date('Y') || (int)$filter['request_year'] < 2018) {
            $filter['request_year'] = 'all';
        }

        $filter['query'] = trim((string)$filter['query']);
    
        return $filter;
    }
    
    public function search_all_pending_staff_requests_by_authority_id(
        $email_authority,  
        $filters = array(),
        $per_page = 0, 
        $page = 0
    ) {   
        $this->db->select(array(
            //Get data staff request
            'request.ID',
            'request.consultant_name',
            'request.business_unit_name',
            'request.client_company_name',
            'request.cost_center',
            'request.creation_date',
            'request.job_title',
            'request.request_type',
            'request.sts_process',
            //Get data staff recruiter
            'app_user_recruiters.ID AS recruiter_ID',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'app_user_recruiters.email AS recruiter_email',
            //Datos de la autorización
            'request_authority.personal_email',
            'request_authority.token',
        ));
        
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_staff_request_authorizations request_authority', 'request_authority.request_ID=request.ID');
        
        $this->db->where('request_authority.personal_email', $email_authority);
        $this->db->where('request_authority.is_authorized', null);
        $this->db->where('request.sts_process', 'pending');

        if (trim((string)$filters['query']) != '') {
            $this->db->group_start();
            $this->db->like('request.job_title', trim($filters['query']));
            $this->db->or_like('request.ID', trim($filters['query']));
            $this->db->group_end();
        }

        $this->db->order_by('request.ID', 'desc');
        $this->db->group_by('request.ID');

        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

    public function count_all_pending_staff_requests_by_authority_id(
        $email_authority,
        $filters = array()
    )
    {
        $this->db->select('request.ID');
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_staff_request_authorizations request_authority', 'request_authority.request_ID=request.ID');
        
        $this->db->where('request_authority.personal_email', $email_authority);
        $this->db->where('request_authority.is_authorized', null);
        $this->db->where('request.sts_process', 'pending');

        if (trim((string)$filters['query']) != '') {
            $this->db->group_start();
            $this->db->like('request.job_title', trim($filters['query']));
            $this->db->or_like('request.ID', trim($filters['query']));
            $this->db->group_end();
        }

        $this->db->group_by('request.ID');
        
        return $this->db->count_all_results();
    }

    public function get_additional_benefits_by_request_id($request_id)
    {
    	$this->db->from('tbl_staff_request_additional_benefits sr_additional_benefit');
   		$this->db->join('tbl_laboral_benefits additional_benefit', 'sr_additional_benefit.benefit_ID=additional_benefit.ID');
   		$this->db->where('sr_additional_benefit.request_ID', $request_id);

   		return $this->db->get()->result();
    }

    public function get_all_additional_benefits_by_request_id($request_id)
    {
        $sr = $this->find($request_id);

    	$this->db->select([
    		'additional_benefit.ID',
    		'additional_benefit.benefit_name',
    		'sr_additional_benefit.benefit_ID AS request_benefit_ID',
    		'sr_additional_benefit.detail',
    	]);

		$this->db->from('tbl_laboral_benefits additional_benefit');
    	$this->db->join('tbl_staff_request_additional_benefits sr_additional_benefit', 'sr_additional_benefit.benefit_ID=additional_benefit.ID AND sr_additional_benefit.request_ID=' . $request_id, 'left');
   		$this->db->where('additional_benefit.company_id', $sr->company_ID);

		return $this->db->get()->result();
    }

    public function get_additional_competences_by_request_id($request_id)
    {
        $this->db->from('tbl_staff_request_additional_competences');
        $this->db->where('request_ID', $request_id);

        return $this->db->get()->result();   
    }

    public function get_fixed_competences_by_request_id($request_id)
    {
        $this->db->from('tbl_staff_request_fixed_competences request_competence');
        $this->db->join('tbl_job_competences competence', 'request_competence.job_competence_ID=competence.ID');
        $this->db->where('request_competence.request_ID', $request_id);

        return $this->db->get()->result();   
    }

    public function add_additional_benefits($additional_benefits, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_additional_benefits');

		foreach ($additional_benefits as $benefit_id => $row) {
    		
    		if (isset($row['checked']) && $row['checked'] == 'true') {
    			
    			$detail = trim($row['detail']);

    			$data_additional_benefit = array(
    				'detail' => empty($detail) ? null : $detail,
    				'benefit_ID' => $benefit_id,
    				'request_ID' => $request_id 
    			);

				$this->db->insert('tbl_staff_request_additional_benefits', $data_additional_benefit);	
    		}
    	}
    }

    public function add_job_functions($job_functions, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_job_functions');

		foreach ($job_functions as $function) {
			
			$function = trim($function);

			if ($function != '') {
				$data_job_functions = array(
					'function' => $function,
					'request_ID' => $request_id
				);

				$this->db->insert('tbl_staff_request_job_functions', $data_job_functions);
			}
		}
    }

    public function add_languages($languages, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_languages');

		foreach ($languages as $row) {
			
			if (isset($row['name']) && trim($row['name']) != '') {

				$language = trim($row['name']);
				$reading_level = trim($row['reading_level']);
				$speaking_level = trim($row['speaking_level']);
				$writing_level = trim($row['writing_level']);

				$data_languages = array(

					'language_name' => $language,
					'reading_level' => $reading_level,
					'speaking_level' => $speaking_level,
					'writing_level' => $writing_level,
					'request_ID' => $request_id
				);

				$this->db->insert('tbl_staff_request_languages', $data_languages);
			}
		}
    }

    public function add_computing($computing, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_computing');

		foreach ($computing as $row) {

		 	if (isset($row['name']) && trim($row['name']) != '') {
		
		 		$name = trim($row['name']);
		 		$level = $row['level'];
		 	
		 		$data_computing = array(
		 			'name' => $name,
		 			'level' => $level,
		 			'type' =>  '',
		 			'request_ID' => $request_id
		 		);
		 	
		 		$this->db->insert('tbl_staff_request_computing', $data_computing);
		 	}
		}
    }

    public function update_status_process($request_id, $status)
    {
        $this->db->where('ID', $request_id);
        return $this->db->update('tbl_staff_requests', array('sts_process' => $status));
    }

    public function add_additional_competences($competences, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_additional_competences');

        foreach ($competences as $competence_name) {
            
            $data_competence = array(
                'competence_name' => trim($competence_name),
                'request_ID' => $request_id
            );

            $this->db->insert('tbl_staff_request_additional_competences', $data_competence);
        }
    }

    public function add_fixed_competences($charge_id, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_fixed_competences');

        $competences = $this->Job_competence->get_active_competences_by_job_charge_id($charge_id);
        
        foreach ($competences as $row_competence) {
            
            $data_competence = array(
                'job_competence_ID' => $row_competence->job_competence_ID,
                'request_ID' => $request_id
            );

            $this->db->insert('tbl_staff_request_fixed_competences', $data_competence);
        }        
    }

    public function add_working_hours($working_hours, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_working_hours');

        foreach ($working_hours as $row) {
            
            if (empty($row['start_day']) || 
                empty($row['end_day']) || 
                empty($row['start_time']) ||
                empty($row['end_time'])) {
                continue;
            }

            $start_time = strtotime($row['start_time'] . ' '. $row['start_time_abr']);
            $end_time = strtotime($row['end_time'] . ' '. $row['end_time_abr']);
                 
            $data = array(
                'start_day' => $row['start_day'],
                'end_day' => $row['end_day'],
                'start_time' => date('H:i', $start_time),
                'end_time' => date('H:i', $end_time),
                'request_ID' => $request_id
            );
            
            $this->db->insert('tbl_staff_request_working_hours', $data);
        }
    }

    public function get_working_hours_by_request_id($request_id)
    {
        $this->db->from('tbl_staff_request_working_hours');
        $this->db->where('request_ID', $request_id);

        return $this->db->get()->result();
    }

    public function reject_staff_request($reason, $request_id)
    {   
        $data = array(
            'reason_rejection' => $reason,
            'sts_process' => 'rejected'
        );

        $this->db->where('ID', $request_id);
        return $this->db->update('tbl_staff_requests', $data);
    }

    public function get_supervisors_employers_by_request_id($request_id)
    {
        $this->db->from('tbl_staff_request_supervised_employers');
        $this->db->where('request_ID', $request_id); 

        return $this->db->get()->result();
    }

    public function get_emails_assigned_employers($request_id)
    {
        $this->db->from('tbl_staff_request_assigned_employers');
        $this->db->where('request_ID', $request_id);
        $employers = $this->db->get()->result();

        $emails = [];

        foreach ($employers as $employer) {
            $employer_info = $this->Employer->get_employer_by_id($employer->employer_ID);

            $emails[] = $employer_info->email;
        }
        return $emails;
    }

    public function get_total_amount_benefits($request_id)
    {
        $results = $this->get_additional_benefits_by_request_id($request_id);
        $amount = 0;

        foreach ($results as $row) {
            
            if ($row->benefit_name != 'EPS') {
                $amount+= (is_numeric(trim((string)$row->detail)) ? trim((string)$row->detail) : 0);
            }
        }
        return $amount;
    }

    public function get_profile_survey_log_by_request_id($request_id)
    {
        $this->db->select([
            'srpsl.date',
            'e.first_name AS employer_name'
        ]);
        $this->db->from('tbl_staff_request_profile_survey_logs srpsl');
        $this->db->join('tbl_employers e', 'srpsl.employer_id=e.ID');
        
        $this->db->where('request_id', $request_id);
        $this->db->order_by('date', 'DESC');
        
        return $this->db->get()->result();
    }

    public function add_resources($data, $request_id)
    {
        $data['request_id'] = $request_id;
        $this->db->insert('tbl_staff_request_resources', $data);
    }

    public function get_resource_by_request_id($request_id = 0)
    {
        $this->db->select([
            'sr_resource.emo_type_id',
            'sr_resource.emo_protocol_detail',
            'sr_resource.emo_expense_type',
            'sr_resource.emo_staff_charge',
            'exam_emo_type.name AS emo_type_name',
            'sr_resource.screening_type_id',
            'sr_resource.screening_expense_type',
            'sr_resource.screening_perform_stage',
            'sr_resource.screening_staff_charge',
            'screening_type.name AS screening_type_name',
            'sr_resource.covid19_type_id',
            'sr_resource.covid19_expense_type',
            'sr_resource.covid19_perform_stage',
            'sr_resource.covid19_staff_charge',
            'exam_covid19_type.name AS covid19_type_name',
            'sr_resource.exam_complementary',
            'sr_resource.exam_complementary_staff_charge',
            'sr_resource.verify_home',
            'sr_resource.verify_home_perform_stage',
            'sr_resource.verify_home_staff_charge',
            'sr_resource.verify_credit',
            'sr_resource.verify_credit_perform_stage',
            'sr_resource.verify_credit_staff_charge',
            'sr_resource.verify_labor',
            'sr_resource.verify_labor_perform_stage',
            'sr_resource.verify_labor_staff_charge',
            'sr_resource.verify_degree',
            'sr_resource.verify_degree_perform_stage', 
            'sr_resource.verify_degree_staff_charge',
            'sr_resource.verify_degree_person',
            'sr_resource.verify_degree_person_perform_stage',
            'sr_resource.verify_degree_person_staff_charge'
        ]);
        $this->db->from('tbl_staff_request_resources sr_resource');
        $this->db->join('tbl_exam_emo_types exam_emo_type', 'exam_emo_type.id=sr_resource.emo_type_id', 'left');
        $this->db->join('tbl_exam_covid19_types exam_covid19_type', 'exam_covid19_type.id=sr_resource.covid19_type_id', 'left');
        $this->db->join('tbl_screening_types screening_type', 'screening_type.id=sr_resource.screening_type_id', 'left');
        $this->db->where('sr_resource.request_id', $request_id);

        return $this->db->get()->row();
    }

    public function get_rrhh_responsibles($staff_request_id = 0)
    {
        $request = $this->Staff_request->find($staff_request_id);
        
        if (!$request) {
            return [];
        }

        $job = $this->Posted_job->get_posted_job_by_request_id($staff_request_id);

        if (!$job) {
            return [];
        }

        //Obtener usuarios asignados manualmente
        $this->db->select([
            'app_users.first_name',
            'app_users.email',
            'app_users.ID AS id',
        ])
        ->from('tbl_employers app_users')
        ->join(
            'tbl_recruitment_rrhh_assignments rrhh_assignments', 
            'app_users.ID=rrhh_assignments.rrhh_user_ID'
        )
        ->where('rrhh_assignments.manual', 1)
        ->where('rrhh_assignments.job_ID', $job->ID)
        ->where('app_users.sts', 'active');
        
        $sql_manual_rrhh_users = $this->db->get_compiled_select();

        //Obtener usuarios rrhh responsables
        $this->db->select([
            'employers.first_name',
            'employers.email',
            'employers.ID AS id',
        ]);
        $this->db->from('tbl_rrhh_responsibles rrhh_responsibles');
        $this->db->join('tbl_employers employers', 'rrhh_responsibles.email=employers.email');
        $this->db->where('employers.sts', 'active');
        $this->db->where('employers.company_ID', $request->company_ID);
        $this->db->where('rrhh_responsibles.company_id', $request->company_ID);
        $this->db->where('rrhh_responsibles.cia_code', $request->no_cia);
        $this->db->where('rrhh_responsibles.client_code', $request->cod_clie);
        $this->db->where('rrhh_responsibles.business_unit_code', $request->cod_business_unit);
        
        $sql_rrhh_responsibles = $this->db->get_compiled_select();

        $sql_rrhh_responsiles = "SELECT 
                    employers.first_name AS first_name,
                    employers.email AS email, 
                    employers.id AS id 
                FROM (" . $sql_manual_rrhh_users . " UNION " . $sql_rrhh_responsibles . ") employers";

        return $this->db->query($sql_rrhh_responsiles)->result();
    }

    public function assign_rrhh_responsibles($request_id)
    {
        $this->load->model('Rrhh_responsible');
        
        $job = $this->Posted_job->get_posted_job_by_request_id($request_id);

        if (!$job) {
            return false;
        }

        $job_id = $job->ID;

        $rrhh_employers = $this->Rrhh_responsible->get_employers_by_request_id($request_id);

        $this->db->where('job_ID', $job_id)->delete('tbl_recruitment_rrhh_assignments');

        foreach ($rrhh_employers as $user) {
            $data_assignment = [
                'job_ID' => $job_id,
                'date_assignment' => date('Y-m-d'),
                'rrhh_user_ID' => $user->id
            ];

            $this->db->insert('tbl_recruitment_rrhh_assignments', $data_assignment);
        }

        return true;
    }

    public function assign_employers_responsibles($request_id)
    {
        $staff_request = $this->Staff_request->find($request_id);

        if (!$staff_request) {
            return false;
        }

        $this->db->select([
            'employers.company_ID AS company_id',
            'permissions.consultant_code AS cia_code',
            'permissions.client_code',
            'permissions.employer_id'
        ]);
        $this->db->from('tbl_employer_permission_clients permissions');
        $this->db->join('tbl_employers employers', 'employers.ID=permissions.employer_id');
        $this->db->join('tbl_employer_profiles employer_profiles', 'employer_profiles.user_id=employers.employer_id');
        $this->db->where('employers.company_ID', $staff_request->company_ID);
        $this->db->where('permissions.consultant_code', $staff_request->no_cia);
        $this->db->where('permissions.client_code', $staff_request->cod_clie);
        $this->db->where('employer_profiles.profile_id', '1'); //Empleador
        $this->db->where('employers.sts', 'active');

        $employer_permissions =  $this->db->get()->result();

        $assigned_employer_date = date('Y-m-d H:i:s');

        foreach ($employer_permissions as $emp_row) {

            $this->db->insert('tbl_staff_request_assigned_employers', [
                'employer_ID' => $emp_row->employer_id,
                'request_ID' => $staff_request->ID,
                'date' => $assigned_employer_date
            ]);
        }

        return true;
    }
}
