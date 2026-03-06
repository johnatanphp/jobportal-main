<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_staff_requests_create_model_3
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function create($data)
    {
        $this->load->model('Employer_staff_request_manage_business_unit');

        $data_staff_request = $this->build_data_request($data);
    
        $data_working_hours = isset($data['working_hours']) ? (array)$data['working_hours'] : [];

        $data_additional_benefits = (array)(isset($data['additional_benefits']) ? $data['additional_benefits'] : []);
      
        $req_by_department = isset($data['req_by_department']) && $data['req_by_department'] ? true : false;
        $data_dep_vacancies = isset($data['dep_vacancies']) ? (array)$data['dep_vacancies'] : [];

    	$this->db->trans_start();
       
        //Crear solicitud
    	$this->db->insert('tbl_staff_requests', $data_staff_request);

    	$request_id = $this->db->insert_id();
    
        //Registrar horarios de trabajos
        $this->add_working_hours($data_working_hours, $request_id);

    	//Registrar beneficios laborales
    	$this->add_additional_benefits($data_additional_benefits, $request_id);

        $template = $data['request_template'];

        //Plantilla Perfil de puesto
        if ($template == '2') {
            $job_profile_id = $data['job_profile'];

            $skills = $this->Job_profile->get_skills_by_job_profile_id($job_profile_id);
            $this->add_additional_competences($skills, $request_id);
    
            $responsibilities = $this->Job_profile->get_responsibilities_by_job_profile_id($job_profile_id);
            $this->add_job_functions($responsibilities, $request_id);
        }

        //Plantilla Layout de puesto
        if ($template == '3') {
            $job_layout_id = $data['job_layout'];

            $skills = $this->Job_layout->get_skills_by_job_layout_id($job_layout_id);
            $this->add_additional_competences($skills, $request_id);
    
            $responsibilities = $this->Job_layout->get_responsibilities_by_job_layout_id($job_layout_id);
            $this->add_job_functions($responsibilities, $request_id);
        }

        if (!$req_by_department) {
            $this->add_department_vacancies($data_dep_vacancies, $request_id);
        }

		$this->db->trans_complete();

		$request_id = $this->db->trans_status() ? $request_id : FALSE;

        if ($request_id) {
            $this->notify_creation_staff_request_by_email($request_id);
            $this->notification_email_staff_request_lib->notify_creation($request_id); 
        } 

        return $request_id;
    }

    private function build_data_request($data)
    {   
        $obj_recruiter = $this->Employer->find($data['user_id']);
        $template = $data['request_template'];

        $job_profile_id = $data['job_profile'];
        $job_layout_id = $data['job_layout'];
        
        $job_title = '';
        $occupational_group = null;
        $general_knowledges  = '';
        $specific_knowledges = '';

        //Plantilla Manual
        if ($template == '1') {
            $job_title = $data['job_title'];
        }

        //Plantilla Perfil laboral
        if ($template == '2') {
            $job_profile = $this->Job_profile->find($job_profile_id);
            $occupational_group = $job_profile->job_charge_ID;
            $job_title = $job_profile->job_title;
            $general_knowledges = $job_profile->education;
            $specific_knowledges = $job_profile->education_min_detail;
        }

        //Plantilla Layout de puesto
        if ($template == '3') {
            $job_layout = $this->Job_layout->find($job_layout_id);
            $occupational_group = $job_layout->job_charge_id;
            $job_title = $job_layout->job_title;
            $general_knowledges = $job_layout->education;
            $specific_knowledges = $job_layout->education_min_detail;
        }

        $replace_employee = isset($data['replace_employee']) ? $data['replace_employee'] : '-';

        if (count(explode('-', $replace_employee)) == 1) {
            $replace_employee = "-" . $replace_employee;
        }

        list($employee_replaced_dni, $employee_replaced_name) = explode('-', $replace_employee);

        $consultant = explode('|', $data['consultant_name']);
        $business_unit = explode('|', $data['business_unit_name']);
        $client_company = explode('|', $data['client_company_name']);

        $delivery_date = date('Y-m-d', strtotime(date('Y-m-d ') . "+ 7 day"));

        if ($data['type_requirement'] == 'ESPECIAL') {
            $delivery_date = $data['delivery_date'];
        }

        $req_by_department = isset($data['req_by_department']) && $data['req_by_department'] ? true : false;
        $data_dep_vacancies = isset($data['dep_vacancies']) ? (array)$data['dep_vacancies'] : [];

        $data = [
            'creation_date' => date('Y-m-d H:i:s'),
            'request_type' => 'external',
            'no_cia' => $consultant[0],
            'consultant_name' => $consultant[1],
            'cod_clie' => $client_company[0],
            'client_company_name' => $client_company[1],
            'cod_business_unit' => $business_unit[0],
            'business_unit_name' => $business_unit[1],
            'cost_center' => $data['cost_center'],
            'job_title' => $job_title,
            'vacancies' => $req_by_department ? $data['vacancies'] : $this->get_vacancies_total($data_dep_vacancies),
            'type_requirement' => $data['type_requirement'],
            'type_expense' => '',
            'name_immediate_boss' => '',
            'charge_immediate_boss' => '',
            'contract_time_qty' => null,
            'contract_time_duration' => null,
            'reason_request' => $data['reason_request'],
            'minimum_salary' => is_numeric($data['minimum_salary']) ? $data['minimum_salary'] : null,
            'maximum_salary' => is_numeric($data['maximum_salary']) ? $data['maximum_salary'] : null,
            'monthly_gross_salary' => is_numeric($data['monthly_gross_salary']) ? $data['monthly_gross_salary'] : null,
            'location' => '',
            'working_hours' => $data['working_hours_manual'],
            'job_address' => '',
            'employee_replaced_dni' => !empty($employee_replaced_dni) ? $employee_replaced_dni : null,
            'employee_replaced_name' => !empty($employee_replaced_name) ? $employee_replaced_name : null,
            'sts' => 'active',
            'sts_process' => 'unassigned',
            'company_ID' => $obj_recruiter->company_ID,
            'recruiter_ID' => $obj_recruiter->ID,
            'eecc_code' =>  '',
            'eecc_description' => '',
            'job_profile_ID' => $template == '2' ? $job_profile_id : null,
            'charge_ID' => $occupational_group,
            'industry_ID' => null,
            'campaign' => $data['campaign'],
            'channel' => $data['channel'],
            'service' => $data['service'],
            'delivery_date' => $delivery_date,
            'health_card' => $data['health_card'],
            'type_contract' => $data['type_contract'],
            'request_model_id' => 3,
            'department' => isset($data['department']) && $req_by_department ? $data['department'] : '',
            'zone' => isset($data['zone']) && $this->is_input_zone_active($data) ? join(',', $data['zone']) : '',
            'zone_comments' => isset($data['zone_comments']) ? $data['zone_comments'] : '',
            'management' => isset($data['management']) ? $data['management'] : '',
            'division' => isset($data['division']) ? $data['division'] : '',
            'gender' => $data['gender'],
            'job_mode' => $data['job_mode'],
            'general_knowledges' => $general_knowledges,
            'specific_knowledges' => $specific_knowledges,
            'additional_comments' => strip_tags(trim($data['additional_comments'])),
            'job_layout_id' => $template == '3' ? $job_layout_id : null
        ];

        return $data;
    }

    private function notify_creation_staff_request_by_email($request_id)
    {
        $sr = $this->Staff_request->find($request_id);

        $employer_admin = $this->Employer->get_admin_employer_by_company_id($sr->company_ID);

        $users = $this->Employer_staff_request_manage_business_unit->get_users_by_staff_request_id($request_id);

        $emails[] = $employer_admin->email;

        foreach  ($users as $row) {
            $emails[] = $row->email;
        }

        $data_email = [
            'staff_request' => $this->Staff_request->find($request_id),
            'url_link' => site_url('employer/staff_requests/show/' . $request_id)   
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);

        $mail_message = load_email_view('email/staff_request_external_created', $data_email);

        $this->email->subject('Solicitud Externa creada');
        $this->email->message($mail_message);     
        $this->email->send();
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

    private function add_additional_benefits($additional_benefits, $request_id)
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

    private function add_job_functions($responsibilities, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_job_functions');

		foreach ($responsibilities as $row) {
			
            $function = trim($row->responsibility);

			if ($function != '') {
				$data_job_functions = [
					'function' => $function,
					'request_ID' => $request_id
				];

				$this->db->insert('tbl_staff_request_job_functions', $data_job_functions);
			}
		}
    }

    public function add_additional_competences($skills, $request_id)
    {
        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_additional_competences');

        foreach ($skills as $row) {
                                    
            $data_competence = [
                'competence_name' => trim($row->skill_name),
                'request_ID' => $request_id
            ];

            $this->db->insert('tbl_staff_request_additional_competences', $data_competence);
        }
    }

    public function add_department_vacancies($dep_vacancies, $request_id)
    {
        $this->db->where('request_id', $request_id);
        $this->db->delete('tbl_staff_request_department_vacancies');

        $list = [];

        foreach ($dep_vacancies as $row) {
            $department = trim($row['department']);
            $list[$department] = $row;
        }

        foreach ($list as $row) {
                                    
            $data_dep_vacancies = [
                'department' => trim($row['department']),
                'vacancies' => trim($row['vacancies']),
                'request_id' => $request_id
            ];

            $this->db->insert('tbl_staff_request_department_vacancies', $data_dep_vacancies);
        }
    }

    public function get_vacancies_total($dep_vacancies)
    {
        $total_vacancies = 0;

        $list = [];

        foreach ($dep_vacancies as $row) {
            $department = trim($row['department']);
            $list[$department] = $row;
        }

        foreach ($list as $row) {
            $total_vacancies+= intval($row['vacancies']);
        }

        return $total_vacancies;
    }

    public function is_input_zone_active($data)
    {
        $req_by_department = isset($data['req_by_department']) && $data['req_by_department'] ? true : false;
       
        if ($req_by_department) {
            return $data['department'] == 'Lima';
        }

        if (!$req_by_department) {
            $dep_vacancies = (array)$data['dep_vacancies'];
            foreach ($dep_vacancies as $row) {
                if ($row['department'] == 'Lima') {
                    return true;
                }
            }
        }

        return false;
    }
}
