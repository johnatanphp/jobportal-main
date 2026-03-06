<?php

if (!function_exists('get_data_staff_request')) {

    function get_data_staff_request($request_id)
    {
        $ci =& get_instance();
        $ci->load->model('Recruitment_process');
        $ci->load->model('Staff_request_gantt');
        $ci->load->model('Recruitment_short_list');
        $ci->load->model('Job_profile');
        $ci->load->model('Country');
        $ci->load->model('Company');
        $ci->load->model('Staff_request_assigned_employer');
        $ci->load->model('Staff_request_department_vacancie');
        $ci->load->model('Job_layout');
        $ci->load->model('Gantt_type');
        $ci->load->model('Staff_request_gantt_activity');
        
        $rs_short_list_model = $ci->Recruitment_short_list;
        $rs_process_model = $ci->Recruitment_process;
        
        $sr_gantt_activity_model = $ci->Staff_request_gantt;
        $sr_model = $ci->Staff_request;
        
        $request = $sr_model->get_external_staff_request_by_id($request_id);
        $request_assigned_employers = $ci->Staff_request_assigned_employer->all(['request_ID' => $request_id]);

        $request_job = $ci->Posted_job->get_posted_job_by_request_id($request_id);
        
        $data['request'] = $request;
        $data['request_assigned_employers'] = $request_assigned_employers;
        $data['working_hours'] = $sr_model->get_working_hours_by_request_id($request_id);
        $data['additional_benefits'] = $sr_model->get_additional_benefits_by_request_id($request_id);
        $data['computing_applicacion'] = $sr_model->get_computing_applications_by_request_id($request_id);
        $data['languages'] = $sr_model->get_languages_by_request_id($request_id);
        $data['job_functions'] = $sr_model->get_job_functions_by_request_id($request_id);
        $data['additional_competences'] = $sr_model->get_additional_competences_by_request_id($request_id);
        $data['fixed_competences'] = $sr_model->get_fixed_competences_by_request_id($request_id);
        $data['request_resource'] =  $sr_model->get_resource_by_request_id($request_id);
        $data['request_posted_job'] = $ci->Posted_job->get_posted_job_by_request_id($request_id);
        
        //Gantt vars
        $data['gantt_types'] = $ci->Gantt_type->all();
        $data['request_gantt'] = $sr_gantt_activity_model->get_gantt_by_request_id($request_id);

        $data['staff_request_department_vacancies'] = $ci->Staff_request_department_vacancie->all(['request_id' => $request_id]);
        $data['exist_short_list_for_posted_job'] = $rs_short_list_model->exist_short_list_for_job_by_request_id($request->ID);
        $data['profile_survey_logs'] = $sr_model->get_profile_survey_log_by_request_id($request_id);

        if ($request_job && $request_job->ID) {
            $data['rs_process'] = $rs_process_model->get_process_by_job_id($request_job->ID);
        }
        
        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        //Cargar datos perfil laboral
        if ($request->job_profile_ID) {
            $job_profile = $ci->Job_profile->find($request->job_profile_ID);
            $company = $ci->Company->find($request->company_ID);
            $data['job_profile'] = $job_profile;
            $data['job_profile_responsibilities'] = $ci->Job_profile->get_responsibilities_by_job_profile_id($request->job_profile_ID);
            $data['job_profile_skills'] = $ci->Job_profile->get_skills_by_job_profile_id($request->job_profile_ID);
            $data['disability_values'] = $ci->Job_profile->disability_values($request->job_profile_ID);
            $data['go_skills'] = $ci->db->get_where('tbl_job_charge_skills', [
                'job_charge_id' => @$job_profile->job_charge_ID
            ])->result();
            $data['results_disability'] = $ci->Job_profile->get_results_disability($request_id);
            $data['results_disability'] = $ci->Job_profile->get_results_disability($request->job_profile_ID);
            $data['disability_values'] = $ci->Job_profile->disability_values($request->job_profile_ID);
            $data['disability_eligibles'] = $ci->Job_profile->get_disability_eligibles($request->job_profile_ID);
            $data['jp_factor_valuations'] = $ci->Job_profile->get_factor_valuations($request->job_profile_ID);
            $data['jp_factor_total_score'] = $ci->Job_profile->get_factor_total_score($request->job_profile_ID);
            $data['jp_benefits'] = $ci->Job_profile->get_benefits($request->company_ID, $request->job_profile_ID);
            $data['country'] = $ci->Country->find($company->country_id);
        }

        //Cargar datos layout de puesto
        if ($request->job_layout_id) {
            $job_layout_id = $request->job_layout_id;
            $job_layout = $ci->Job_layout->find($job_layout_id);
            $data['job_layout'] = $job_layout;
            $data['jl_skills'] = $ci->Job_layout->get_skills_by_job_layout_id($job_layout_id);
            $data['jl_responsibilities'] = $ci->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
            $data['jl_occupational_group'] = $ci->Job_charge->get_job_charge_by_id($job_layout->job_charge_id);
            $data['go_skills'] = $ci->db->get_where('tbl_job_charge_skills', [
                'job_charge_id' => $job_layout->job_charge_id
            ])->result();
        
            // $data['jl_results_disability'] = $ci->Job_layout->get_results_disability($job_layout_id);
            // $data['jl_disability_values'] = $ci->Job_layout->disability_values($job_layout_id);
            // $data['jl_disability_eligibles'] = $ci->Job_layout->get_disability_eligibles($job_layout_id);
            $data['jl_disability_options'] = $ci->Job_layout->get_results_disability_options($job_layout_id);
            $data['jl_factor_valuations'] = $ci->Job_layout->get_factor_valuations($job_layout_id);
            $data['jl_factor_total_score'] = $ci->Job_layout->get_factor_total_score($job_layout_id);
            $data['jl_benefits'] = $ci->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);

            $company = $ci->Company->find($job_layout->company_id);
            $data['country'] = $ci->Country->find($company->country_id);
        }
        
        return $data;
    }
}

if (!function_exists('get_data_staff_request_internal')) {
    function get_data_staff_request_internal($request_id)
    {
        $ci =& get_instance();
        $ci->load->model('Recruitment_process');
        $ci->load->model('Staff_request_gantt');
        $ci->load->model('Recruitment_short_list');
        $ci->load->model('Staff_request_authoritation');
        $ci->load->model('Mof');
        $ci->load->model('Country');
        $ci->load->model('Staff_request_assigned_employer');
        $ci->load->model('Job_layout');
        $ci->load->model('Gantt_type');
        $ci->load->model('Staff_request_gantt_activity');

        $sr_model = $ci->Staff_request;
        $sr_gantt_activity_model = $ci->Staff_request_gantt;
       
        $rs_short_list_model = $ci->Recruitment_short_list;
        $rs_process_model = $ci->Recruitment_process;
        
        $request = $sr_model->get_internal_staff_request_by_id($request_id);
        $request_assigned_employers = $ci->Staff_request_assigned_employer->all(['request_ID' => $request_id]);
        $request_job = $ci->Posted_job->get_posted_job_by_request_id($request_id);
       
        $data['request'] = $request;
        $data['request_assigned_employers'] = $request_assigned_employers;
        $data['working_hours'] = $sr_model->get_working_hours_by_request_id($request_id);
        $data['additional_benefits'] = $sr_model->get_additional_benefits_by_request_id($request_id);
        $data['request_posted_job'] = $ci->Posted_job->get_posted_job_by_request_id($request_id);
        
        //Gantt vars
        $data['gantt_types'] = $ci->Gantt_type->all();
        $data['request_gantt'] = $sr_gantt_activity_model->get_gantt_by_request_id($request_id);

        $data['exist_short_list_for_posted_job'] = $rs_short_list_model->exist_short_list_for_job_by_request_id($request->ID);
        $data['profile_survey_logs'] = $sr_model->get_profile_survey_log_by_request_id($request_id);

        $data['rs_process'] = null; 
        
        if ($request_job && $request_job->ID) {
            $data['rs_process'] = $rs_process_model->get_process_by_job_id($request_job->ID);
        }

        //Cargar datos Mof
        if ($request->mof_ID) {
            $mof_model = $ci->Mof;
            $mof = $mof_model->get_mof_by_id($request->mof_ID);
            $data['mof'] = $mof;
            $data['mof_belonging_areas'] = $mof_model->get_belonging_areas_by_mof_id($mof->ID);
            $data['mof_skills'] = $mof_model->get_skills_by_mof_id($mof->ID);
            $data['mof_responsibilities'] = $mof_model->get_responsibilities_by_mof_id($mof->ID);
            $data['mof_indicators'] = $mof_model->get_indicators_by_mof_id($mof->ID);
            $data['disability_values'] = $mof_model->Mof->disability_values($mof->ID);
            $data['go_skills'] = $ci->db->get_where('tbl_job_charge_skills', [
                'job_charge_id' => $mof->job_charge_id
            ])->result();
            $data['results_disability'] = $ci->Mof->get_results_disability($mof->ID);
            $data['disability_values'] = $ci->Mof->disability_values($mof->ID);
            $data['disability_eligibles'] = $ci->Mof->get_disability_eligibles($mof->ID);
            $data['mof_factor_valuations'] = $ci->Mof->get_factor_valuations($mof->ID);
            $data['mof_factor_total_score'] = $ci->Mof->get_factor_total_score($mof->ID);
            $data['mof_benefits'] = $ci->Mof->get_benefits($mof->company_id, $mof->ID);

            $company = $ci->Company->find($mof->company_id);
            $data['country'] = $ci->Country->find($company->country_id);

            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];
        }

        //Cargar datos Layout de puesto
        if ($request->job_layout_id) {
            $job_layout_id = $request->job_layout_id;
            $job_layout = $ci->Job_layout->find($job_layout_id);
            $data['job_layout'] = $job_layout;
            $data['jl_skills'] = $ci->Job_layout->get_skills_by_job_layout_id($job_layout_id);
            $data['jl_responsibilities'] = $ci->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
            $data['jl_occupational_group'] = $ci->Job_charge->get_job_charge_by_id($job_layout->job_charge_id);
            $data['go_skills'] = $ci->db->get_where('tbl_job_charge_skills', [
                'job_charge_id' => $job_layout->job_charge_id
            ])->result();
        
            //$data['jl_results_disability'] = $ci->Job_layout->get_results_disability($job_layout_id);
            //$data['jl_disability_values'] = $ci->Job_layout->disability_values($job_layout_id);
            //$data['jl_disability_eligibles'] = $ci->Job_layout->get_disability_eligibles($job_layout_id);
            $data['jl_disability_options'] = $ci->Job_layout->get_results_disability_options($job_layout_id);
            $data['jl_factor_valuations'] = $ci->Job_layout->get_factor_valuations($job_layout_id);
            $data['jl_factor_total_score'] = $ci->Job_layout->get_factor_total_score($job_layout_id);
            $data['jl_benefits'] = $ci->Job_layout->get_benefits($job_layout->company_id, $job_layout_id);

            $company = $ci->Company->find($job_layout->company_id);
            $data['country'] = $ci->Country->find($company->country_id);
            $data['rys_stages'] = [
                '2' => 'LONG LIST',
                '5' => 'SHORT LIST',
                '6' => 'SELECCIÓN'
            ];
        }

        return $data;
    }
}

if (!function_exists('status_process_request_text')) {

    function status_process_request_text($str)
    {
        $data = array(
            'pending' => 'Pendiente',
            'unassigned' => 'Sin asignar',
            'assigned' => 'Asignada',
            'published' => 'Publicada',
            'rejected' => 'Rechazada',
            'canceled' => 'Cancelada',
            'suspended' => 'Suspendida',
            '8' => 'No iniciado',
            '9' => 'Iniciado' 
        );

        return isset($data[$str]) ? $data[$str] : $str;
    }
}

if (!function_exists('type_recruiter_text')) {

    function type_recruiter_text($str)
    {
        $data = array(
            'internal' => 'Interno',
            'external' => 'Externo',
            'internal-external' => 'Interno / Externo', 
        );

        return isset($data[$str]) ? $data[$str] : $str;
    }
}

if (!function_exists('reason_request_text')) {

    function reason_request_text($str)
    {
        $ci =& get_instance();

        $ci->db->from('tbl_staff_request_type_reasons');
        $ci->db->where('id', $str);

        $row = $ci->db->get()->row();

        return $row ? $row->name : $str;
    }
}

if (!function_exists('request_type_text')) {

    function request_type_text($str)
    {
        $data = array(
            'internal' => 'Interna',
            'external' => 'Externa',    
        );

        return isset($data[$str]) ? $data[$str] : $str;
    }
}

if (!function_exists('get_options_type_screening')) {
    function get_options_type_screening()
    {
        $options = [
            'No aplica',
            'Básico',
            'Integral',
        ];

        return $options;
    }
}

if (!function_exists('get_options_exam_type_covid')) {
    function get_options_exam_type_covid()
    {
        $options = [
            0 => 'No aplica',
            1 => 'PRUEBA RAPIDA',
            2 => 'PRUEBA MOLECULAR PCR',
            3 => 'PRUEBA ANTIGENO',
            4 => 'PRUEBA ELISA',
            //5 => 'PRUEBA ELECTROQUIMIOLUMINISCENCIA',
            6 => 'PRUEBA CLIA',
            7 => 'PRUEBA ECLIA',
            8 => 'PRUEBA IFI'
        ];

        return $options;
    }
}

if (!function_exists('get_options_type_emo')) {
    function get_options_type_emo()
    {
        $options = [
            'No aplica',
            'PROTOCOLO 1. ADMINISTRATIVO',
            'PROTOCOLO 2. MANIPULADORES DE ALIMENTOS',
            'PROTOCOLO 3. MERCADERISTAS',
            'PROTOCOLO 4. CONDUCTOR DE VEHÍCULOS',
            'PROTOCOLO 5. JARDINERO',
            'PROTOCOLO 6. LIMPIEZA',
            'PROTOCOLO 7. MANTENIMIENTO Y SERVICIOS GENERALES',
            'PROTOCOLO 8. PERSONAL DE SALUD/ASISTENCIAL',
            'PROTOCOLO 9. OPERARIO SOLDADOR',
            'PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE'
        ];
        return $options;
    }
}


if (!function_exists('options_exams_complementary')) {
    function options_exams_complementary()
    {
        $options = [
            'No aplica',
            'ALTURA ESTRUCTURAL',
            'MANEJO',
            'MANIPULADOR DE ALIMENTOS',
            'PLOMO SEGÚN CARGO Y UNIDAD MINERA A VISITAR'
        ];
        
        return $options;
    }
}

if (!function_exists('options_risk_criteria')) {
    function options_risk_criteria()
    {
        $options = [
            'RIESGO BAJO DE EXPOSICIÓN O DE PRECAUCIÓN',
            'RIESGO MEDIANO DE EXPOSICIÓN',
            'RIESGO ALTO DE EXPOSICIÓN',
            'RIESGO MUY ALTO EXPOSICIÓN'
        ];
        return $options;
    }
}

if (!function_exists('level_text')) {

    function level_text($str)
    {
        $data = array(
            'basic' => 'Básico',
            'intermediate' => 'Intermedio',
            'advanced' => 'Avanzado',   
        );

        return isset($data[$str]) ? $data[$str] : $str;
    }
}

if (!function_exists('modality_contracting_text')) {

    function modality_contracting_text($str)
    {
        $data = array(
            'outsourcing' => 'Tercerización',
            'intermediation' => 'Intermediación',
            'direct_form_client' => 'Planilla directa del cliente', 
            'others' => 'Otros'
        );
        
        return isset($data[$str]) ? $data[$str] : $str;
    }
}

if (!function_exists('salary_delivery_period_text')) {

    function salary_delivery_period_text($str)
    {
        $data = array(
            'weekly' => 'Semanal',
            'biweekly' => 'Quicenal',
            'monthly' => 'Mensual', 
        );
        
        return isset($data[$str]) ? $data[$str] : $str;
    }
}

if (!function_exists('is_staff_request_user_manage')) {
    function is_staff_request_user_manage($user_id = 0, $request_id = 0)
    {
        $ci =& get_instance();
        $ci->load->model(
            'Employer_staff_request_manage_business_unit'
        );
        
        $users = $ci->Employer_staff_request_manage_business_unit
                    ->get_users_by_staff_request_id($request_id);

        foreach ($users as $user_row) {
            if ($user_row->ID == $user_id) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('get_diff_benefit_value')) {
    function get_diff_benefit_value($request_benefit, $template_benefits) {
        foreach ((array)$template_benefits as $row) {
            if ($request_benefit->benefit_ID == $row->benefit_id) {
                if (number_format((float)trim((string)$request_benefit->detail), 2, '.', '') != number_format((float)trim((string)$row->maximum), 2, '.', '')) {
                    return [
                        'maximum' => number_format((float)trim((string)$row->maximum), 2, '.', '')
                    ];
                }
            } 
        }
        return false;
    }
}

function sr_gantt_get_diff_days($start_date, $end_date)
{
	$datetime1 = new DateTime($start_date);
	$datetime2 = new DateTime($end_date);

	$interval = $datetime1->diff($datetime2);

	return $interval->format('%a');
}

function sr_gantt_create_header_days($start_date_gantt, $end_date_gantt)
{
	$start_date_task_time = new DateTime($start_date_gantt);
	$total_days = sr_gantt_get_diff_days($start_date_gantt, $end_date_gantt);	
	$col_days = '';

	for ($i = 0; $i < ($total_days + 1); $i++) {
		$col_days.='<th class="bg-header">' . $start_date_task_time->format('d') . '</th>';
		$start_date_task_time->add(new DateInterval('P1D'));
	}
	return $col_days;
}

function sr_gantt_create_header_months($start_date_gantt, $end_date_gantt)
{
	$start_date = new DateTime($start_date_gantt);
	$end_date = new DateTime($end_date_gantt);
    
    $start_date_month = (new DateTime($start_date_gantt))->modify('first day of this month');
    $end_date_month = (new DateTime($end_date_gantt))->modify('first day of this month');
    $diff_month = $start_date_month->diff($end_date_month)->format('%M') + 1;

	$col_months = '';
	
	for ($i = 0; $i < $diff_month; $i++) { 
		$tmp_end_date = new DateTime($start_date->format('Y-m-d'));
		$tmp_end_date->modify('last day of this month');

		if ($tmp_end_date > $end_date) {
			$tmp_end_date = $end_date;
		}

		$total_days = sr_gantt_get_diff_days($start_date->format('Y-m-d'), $tmp_end_date->format('Y-m-d'));	
		$col_months.='<th class="bg-header" colspan="' . ($total_days + 1) . '">' .  ucwords(_date_locale_format(strtotime($start_date->format('Y-m-d')), 'MMMM y')) . '</th>';

		$start_date = $tmp_end_date;		
		$start_date->add(new DateInterval('P1D'));
	}

	return $col_months;
}

function sr_gantt_create_content_gantt_activities($data_gantt, $start_date_gantt, $end_date_gantt, $row_activity)
{
	$total_days = sr_gantt_get_diff_days($start_date_gantt, $end_date_gantt) + 1;

	$tmp_current_date = new DateTime($start_date_gantt);
	$start_date_activity = new DateTime($row_activity->start_date);
	$end_date_activity = new DateTime($row_activity->end_date);
	$col_content_gantt = '';

	for ($i = 0; $i < $total_days; $i++) {
		$class_selected = ''; 

		if ($tmp_current_date >= $start_date_activity && 
			$end_date_activity >= $tmp_current_date) {
			$class_selected = 'selected';
		}

		$day_format = $tmp_current_date->format('l');

		if ($data_gantt->ignore_weekend && 
			($day_format == 'Saturday' || $day_format == 'Sunday')) {
			$class_selected = '';
		}

		$col_content_gantt.= '<td class="' . $class_selected . '"></td>';
		$tmp_current_date->add(new DateInterval('P1D'));
	}

	return $col_content_gantt;
}
