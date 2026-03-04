<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_request_create_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function create($input_data)
    {
        $this->load->model('Job_layout');
        $this->load->model('Staff_request');

        list($is_success, $message) = $this->validate($input_data);
        
        if (!$is_success) {
            return [
                'status' => false,
                'message' => $message
            ];
        }
            
        $request_user_id = $this->session_employer_lib->get_data('user_id');
        $employer = $this->Employer->find($request_user_id);

        $this->db->trans_start();

        $created_at = date('Y-m-d H:i:s');

        $consultant = $this->db->get_where('tbl_workflow_consultants', [
            'code' => $input_data['consultant_id'],
            'company_id' => $employer->company_ID
        ])->row();

        $client = $this->db->get_where('tbl_workflow_clients', [
            'code' => $input_data['client_id'],
            'company_id' => $employer->company_ID
        ])->row();

        $business_unit_id = $input_data['business_unit_id'] ?? 'TM';
        $business_unit = $this->db->get_where('tbl_business_units', [
            'business_unit_code' => $business_unit_id,
            'company_id' => $employer->company_ID
        ])->row();

        $cost_center_id = $input_data['cost_center_id'] ?? '';
        
         $cost_center = $this->db->get_where('tbl_workflow_cost_centers', [
            'code' => $cost_center_id,
            'business_unit_code' => $business_unit_id,
            'client_code' => $input_data['client_id'],
            'cia_code' => $input_data['consultant_id'],
            'company_id' => $employer->company_ID
        ])->row();

        $sr_has_penalty = $cost_center ? $cost_center->has_penalty : 0;

        $job_layout = $this->Job_layout->find($input_data['job_layout_id']);
            
        $data_sr_detail = [
            'no_cia' => $consultant->code,
            'consultant_name' => $consultant->name,
            'cod_clie' => $client->code,
            'client_company_name' => $client->name,
            'cod_business_unit' => $business_unit->business_unit_code,
            'business_unit_name' => $business_unit->business_unit_name,
            'cost_center' => $input_data['cost_center_id'] ?? '',
            'type_expense' => $input_data['expense_type_id'] ?? '',
            'job_title' => $job_layout->job_title,
            'request_type' => 'external',
            'company_ID' => $employer->company_ID,
            'recruiter_ID' => $employer->ID,
            'employer_ID' => null,
            'creation_date' => $created_at,
            'sts_process' => '8', //no Iniciado
            'request_model_id' => 4, //Modelo 4
            'reason_request' => $input_data['type_reason_id'],
            'job_layout_id' => $job_layout->id,
            'campaign_id' => $input_data['campaign_id'],
            'vacancies' => $this->get_total_vacancies($input_data),
            'job_duration' => $input_data['job_duration'],
            'start_date_work' => $input_data['start_date_work'],
            'end_date_work' => $input_data['end_date_work'],
            'start_hour_work' => $input_data['start_hour_work'],
            'end_hour_work' => $input_data['end_hour_work'],
            'job_location_type_id' => $input_data['job_location_type_id'],
            'gender' => $input_data['gender'],
            'job_description' => $input_data['job_description'],
            'job_worker_education_id' => $input_data['job_worker_education_id'],
            'job_worker_experience_months' => $input_data['job_worker_experience_months'],
            'job_worker_experience_channel' => $input_data['job_worker_experience_channel'],
            'job_worker_experience_client' => $input_data['job_worker_experience_client'],
            'job_worker_national' => $input_data['job_worker_national'],
            'job_worker_health_carnet' => $input_data['job_worker_health_carnet'],
            'job_worker_health_insurance' => $input_data['job_worker_health_insurance'],
            'job_worker_health_disabilities' => $input_data['job_worker_health_disabilities'],
            'job_list_type' => $input_data['job_list_type'],
            'delivery_date' => $input_data['date_due'],
            'stage_group_id' => 2,
            'screening_phase_id ' => $input_data['screening_phase_id'],
            'has_penalty' => $sr_has_penalty
        ];
            
        $this->db->insert('tbl_staff_requests', $data_sr_detail);
        $sr_id = $this->db->insert_id();

        if (!$sr_id) {
            return [
                'status' => false,
                'message' => 'Error al crear el detalle de la solicitud',
            ];
        }

        // $this->db->insert('tbl_staff_request_assigned_employers', [
        //     'date' => $created_at,
        //     'employer_ID' => $employer->ID,
        //     'request_ID' => $sr_id
        // ]);

        //Registrar dias de trabajo
        $working_days = $input_data['job_working_days'];

        foreach ($working_days as $day)  {
            $this->db->insert('tbl_staff_request_working_days', [
                'day' => $day,
                'request_id' => $sr_id
            ]);
        }

        //Registrar Habilidades
        $seeker_skills = []; //$input_data['job_worker_skills'] ?? [];

        foreach ($seeker_skills as $skill_id)  {
            $this->db->insert('tbl_staff_request_seeker_skills', [
                'skill_id' => $skill_id,
                'request_id' => $sr_id
            ]);
        }

        //Registrar experiencia laborales
        $job_experiences = $input_data['job_worker_experience'];

        foreach ($job_experiences as $experience_id)  {
            $this->db->insert('tbl_staff_request_job_experiences', [
                'experience_id' => $experience_id,
                'request_id' => $sr_id
            ]);
        }

        //Registrar ubicaciones
        if ($input_data['job_list_type'] == 1) {
             
            $job_locations = $input_data['job_locations'];

            foreach ($job_locations as $job_location)  {
                $this->db->insert('tbl_staff_request_job_locations', [
                    'country_id' => $job_location['country_id'],
                    'department_id' => $job_location['department_id'],
                    'province_id' => $job_location['province_id'],
                    'district_id' => $job_location['district_id'],
                    'vacancies' => $job_location['vacancies'],
                    'request_id' => $sr_id
                ]);

                $this->db->insert('tbl_recruitment_process', [
                    'country_id' => $job_location['country_id'],
                    'department_id' => $job_location['department_id'],
                    'province_id' => $job_location['province_id'],
                    'district_id' => $job_location['district_id'],
                    'vacancies' => $job_location['vacancies'],
                    'request_id' => $sr_id
                ]);
            }
        }

        //Registrar locales comerciales
        if ($input_data['job_list_type'] == 2) {
             
            $commercial_premises = $input_data['job_commercial_premises'];

            foreach ($commercial_premises as $local_pos)  {
                $this->db->insert('tbl_staff_request_commercial_premises', [
                    'commercial_premise_id' => $local_pos['commercial_premise_id'],
                    'vacancies' => $local_pos['vacancies'],
                    'request_id' => $sr_id
                ]);

                $this->db->insert('tbl_recruitment_process', [
                    'commercial_premise_id' => $local_pos['commercial_premise_id'],
                    'vacancies' => $local_pos['vacancies'],
                    'request_id' => $sr_id
                ]);
            }
        }

        //Registrar Zonas
        if ($input_data['job_list_type'] == 3) {
             
            $zones = $input_data['zones'];

            foreach ($zones as $zone)  {
                $this->db->insert('tbl_recruitment_process', [
                    'zone_id' => $zone['zone_id'],
                    'vacancies' => $zone['vacancies'],
                    'request_id' => $sr_id
                ]);
            }
        }

        //Crear el empleo
        $qualification = $this->db->get_where('tbl_qualifications', [
            'ID' => $data_sr_detail['job_worker_education_id']
        ])->row();

        $seeker_skills = []; //$input_data['job_worker_skills'] ?? [];
        $result_skills = [];

        if (count($seeker_skills) > 0) {
            $this->db->from('tbl_skills');
            $this->db->where_in('ID', $seeker_skills);
            $result_skills = $this->db->get()->result();
        }
       
        $job_skills = [];
        
        foreach ($result_skills as $skill) {
            $job_skills[] = trim($skill->skill_name);
        }
        
        $this->db->insert('tbl_post_jobs', [
            'employer_ID' => $employer->ID,
            'job_title' => $data_sr_detail['job_title'],
            'company_ID' => $data_sr_detail['company_ID'],
            'industry_ID' => 66,
            'dated' => date('Y-m-d'),
            'sts' => 'pending',
            'country' => 'Perú',
            'last_date' => $data_sr_detail['delivery_date'],
            'vacancies' => $this->get_total_vacancies($input_data),
            'has_questions' => 'no',
            'show_salary_in_ad' => 'no',
            'job_mode' => 'full_time',
            'qualification' => $qualification ? $qualification->text : '', 
            'allow_people_disability' => $data_sr_detail['job_worker_health_disabilities'] == 1 ? 'yes' : 'no',
            'required_skills' => join(', ', $job_skills),
            'request_ID' => $sr_id
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return [
                'status' => false,
                'message' => 'Error al crear la solicitud'
            ];
        }
    
        $response_data = [
            'id' => (string)$sr_id
        ];

        if (isset($input_data['send_email_recruiter']) && $input_data['send_email_recruiter'] == 1) {
            $this->send_email_recruiter($sr_id);
        }

        return apiv2_response(
            true, 
            'Solicitud ha sido creada', 
            $response_data
        );
    }

    public function validate($input_data)
    {
        try { 
            $this->validate_data_request($input_data);
            $this->validate_data($input_data);
            return [true, 'Ok'];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }

        return [false, "Un error ha ocurrido"];
    }

    public function validate_data($input_data)
    {   
        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        $date_due = (new DateTime())->add(new DateInterval('P2D'));
        $this->form_validation->set_rules('consultant_id', 'consultant_id', 'required');
        $this->form_validation->set_rules('client_id', 'client_id', 'required');
        $this->form_validation->set_rules('business_unit_id', 'business_unit_id', 'trim');
       // $this->form_validation->set_rules('cost_center_id', 'cost_center_id', 'required');
        //$this->form_validation->set_rules('expense_type_id', 'expense_type_id', 'required');
        $this->form_validation->set_rules('type_reason_id', 'type_reason_id', 'required|in_list[15,16]');
        $this->form_validation->set_rules('date_due', 'date_due', 'required|valid_date|date_greater_than_equal_to[' . $date_due->format('Y-m-d') . ']');
        $this->form_validation->set_rules('type_reason_id', 'type_reason_id', 'required|in_list[15,16]');
        $this->form_validation->set_rules('job_list_type', 'job_list_type', 'required|in_list[1,2,3]');
    
        $job_list_type = $input_data['job_list_type'] ?? '';

        if ($job_list_type == 1) {
            $this->form_validation->set_rules('job_locations[]', 'job_locations[]', 'trim|required');

            $job_locations = $input_data['job_locations'] ?? [];

            foreach ($job_locations as $index => $row) {

                $country_id = $row['country_id'] ?? '';
                $department_id = $row['department_id'] ?? '';
                $province_id = $row['province_id'] ?? '';
                $district_id = $row['district_id'] ?? '';

                $this->form_validation->set_rules(
                    'job_locations[' . $index . '][country_id]', 'job_locations[' . $index . '][country_id]', [
                    'required', 'integer', ['check_country_id', [$this, 'check_country_id']]
                ]);
                $this->form_validation->set_rules(
                    'job_locations[' . $index . '][department_id]', 'job_locations[' . $index . '][department_id]', 'required|integer'
                );
                $this->form_validation->set_rules(
                    'job_locations[' . $index . '][province_id]', 'job_locations[' . $index . '][province_id]', 'required|integer'
                );

                $this->form_validation->set_rules(
                    'job_locations[' . $index . '][district_id]', 'job_locations[' . $index . '][district_id]', 'required|integer'
                );

                $this->form_validation->set_rules(
                    'job_locations[' . $index . ']', 'job_locations[' . $index . ']', [['check_ubigeo', function() use ($country_id, $department_id, $province_id, $district_id) {
                        return $this->check_ubigeo($country_id,  $department_id, $province_id, $district_id);
                    }]
                ]);

                $this->form_validation->set_rules(
                    'job_locations[' . $index . '][vacancies]', 'job_locations[' . $index . '][vacancies]', 'required|integer|greater_than[0]'
                );
            }
        }
        
        if ($job_list_type == 2) {
            $this->form_validation->set_rules('job_commercial_premises[]', 'job_commercial_premises[]', 'trim|required');

            $job_commercial_premises = $input_data['job_commercial_premises'] ?? [];

            foreach ($job_commercial_premises as $index => $row) {

                $this->form_validation->set_rules(
                    'job_commercial_premises[' . $index . '][commercial_premise_id]', 'job_commercial_premises[' . $index . '][commercial_premise_id]', [
                    'required', 'integer', ['check_commercial_premise_id', [$this, 'check_commercial_premise_id']]
                ]);
                $this->form_validation->set_rules(
                    'job_commercial_premises[' . $index . '][vacancies]', 'job_commercial_premises[' . $index . '][vacancies]', 'required|integer|greater_than[0]'
                );
            }
        }

         if ($job_list_type == 3) {
            $this->form_validation->set_rules('zones[]', 'zones[]', 'trim|required');

            $zones = $input_data['zones'] ?? [];

            foreach ($zones as $index => $row) {

                $this->form_validation->set_rules(
                    'zones[' . $index . '][zone_id]', 'zones[' . $index . '][zone_id]', [
                    'required', 'integer', ['check_zone_id', [$this, 'check_zone_id']]
                ]);
                $this->form_validation->set_rules(
                    'zones[' . $index . '][vacancies]', 'zones[' . $index . '][vacancies]', 'required|integer|greater_than[0]'
                );
            }
        }
            
        $this->form_validation->set_message('required', 'El campo %s es requerido');
        $this->form_validation->set_message('check_country_id', 'El campo %s tiene un valor incorecto');
        $this->form_validation->set_message('check_ubigeo', 'La relación del ubigeo del campo %s no existe en el maestro de ubigeos');
        $this->form_validation->set_message('check_commercial_premise_id', 'El campo %s tiene un valor incorecto');
        $this->form_validation->set_message('check_zone_id', 'El campo %s tiene un valor incorecto');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            throw new \Exception(current($message_error), -1);
        }
    }

    public function check_country_id($str)
    {
        if ($str === null) {
            return true;
        }

        $count = $this->db->get_where('tbl_countries', ['ID' => $str])->num_rows();

        if ($count == 0) {
            return false;
        }

        return true;
    }

    public function check_commercial_premise_id($str)
    {
        if ($str === null) {
            return true;
        }

        $count = $this->db->get_where('tbl_commercial_premises', ['id' => $str])->num_rows();

        if ($count == 0) {
            return false;
        }

        return true;
    }

    public function check_zone_id($str)
    {
        if ($str === null) {
            return true;
        }

        $count = $this->db->get_where('tbl_ubigeo_zones', ['id' => $str, 'active' => 1])->num_rows();

        if ($count == 0) {
            return false;
        }

        return true;
    }

    public function check_ubigeo($country_id, $department_id, $province_id, $district_id)
    {
        $count = $this->db->get_where(
            'tbl_ubigeos', [
                'country_id' => $country_id,
                'order_administrative1_code' => $department_id,
                'order_administrative2_code' => $province_id,
                'order_administrative3_code' => $district_id
            ]
        )->num_rows();

        if ($count == 0) {
            return false;
        }

        return true;
    }

    private function validate_data_request($input_data)
    {
        $employer_id = $this->session_employer_lib->get_data('user_id');
        $reques_user = $this->Employer->find($employer_id);

        if (!$reques_user) {
            throw new \Exception('Valor del campo request_user_id es incorrecto', -1);
        }

        $consultant = $this->db->get_where('tbl_workflow_consultants', [
            'code' => $input_data['consultant_id'],
            'company_id' => $reques_user->company_ID
        ])->row();

        if (!$consultant) {
            throw new \Exception('Valor del campo consultant_id es incorrecto', -1);
        }

        $client = $this->db->get_where('tbl_workflow_clients', [
            'code' => $input_data['client_id'],
            'company_id' => $reques_user->company_ID
        ])->row();

        if (!$client) {
            throw new \Exception('Valor del campo client_id es incorrecto', -1);
        }

        //Validar Layout
        $job_layout_id = $input_data['job_layout_id'] ?? '';
        $job_layout = $this->db->get_where('tbl_job_layouts', [
            'id' => $job_layout_id,
            'company_id' => $reques_user->company_ID,
            'active' => 1
        ])->row();

        if (!$job_layout) {
            throw new \Exception(
                'EL valor del campo job_layout_id' . ($job_layout_id != '' ? ' - ' . $job_layout_id : '' ) . ' es incorrecto o esta inactivo', 
                -1
            );
        }

        //Validar Campañia
        $campaign_id = $input_data['campaign_id'] ?? '';
        $campaign = $this->db->get_where('tbl_campaigns', [
            'id' => $campaign_id,
            'active' => 1
        ])->row();

        if (!$campaign) {
            throw new \Exception(
                'EL valor del campo campaign_id' . ($campaign_id != '' ? ' - ' . $campaign_id : '' ) . ' es incorrecto o esta inactivo', 
                -1
            );
        }

        //Validar fecha inicio labores
        $start_date_work = $input_data['start_date_work'] ?? '';
    
        if (!$this->is_valid_date($start_date_work)) {
            throw new \Exception(
                'EL valor del campo start_date_work' . ($start_date_work != '' ? ' - ' . $start_date_work : '' ) . ' debe tener un valor correcto y formato YYYY-MM-DD', 
                -1
            );
        }

        //Validar fecha fin labores
        $end_date_work = $input_data['end_date_work'] ?? '';

        if (isset($input_data['end_date_work']) && 
            !$this->is_valid_date($end_date_work)) {
            throw new \Exception(
                'EL valor del campo end_date_work' . ($end_date_work != '' ? ' - ' . $end_date_work : '' ) . ' debe tener un valor correcto y formato YYYY-MM-DD', 
                -1
            );
        }

        if (isset($input_data['end_date_work']) && strtotime($start_date_work) > strtotime($end_date_work)) {
            throw new \Exception(
                'La fecha del campo end_date_work debe ser mayor o igual al campo start_date_work', 
                -1
            );
        }

        // Validar screening del candidato
        if (!$input_data['screening_phase_id']) {
            throw new \Exception('Valor del campo screening_phase_id es incorrecto', -1);
        }

        //Validar hora inicio trabajo
        $start_hour_work = $input_data['start_hour_work'] ?? '00:00';

        if ($start_hour_work == '00:00' || !$this->is_valid_time($start_hour_work)) {
            throw new \Exception('La Hora del campo start_hour_work debe tener un valor correcto y formato 24 horas HH:MM', -1);
        }

        //Validar hora fin labores
        $end_hour_work = $input_data['end_hour_work'] ?? '00:00';

        if ($end_hour_work == '00:00' || !$this->is_valid_time($end_hour_work)) {
            throw new \Exception('La Hora del campo end_hour_work debe tener un valor correcto y formato 24 horas HH:MM', -1);
        }

        if (strtotime($start_hour_work) >= strtotime($end_hour_work)) {
            throw new \Exception(
                'La hora del campo end_hour_work debe ser mayor a la hora del campo start_hour_work', 
                -1
            );
        }

        //Validar Genero
        $gender = $input_data['gender'] ?? '';
        $gender_list = ['male', 'female', 'both'];

        if (!in_array($gender, $gender_list)) {
            throw new \Exception(
                'El valor del campo gender es incorrecto', 
                -1
            );
        }

        //Validar tipo de ubicacion
        $location_type_id = $input_data['job_location_type_id'] ?? '';

        $location_type = $this->db->get_where('tbl_staff_request_job_location_types', [
            'id' => $location_type_id,
            'active' => 1
        ])->row();

        if (!$location_type) {
            throw new \Exception(
                'EL valor del campo job_location_type_id' . ($location_type_id != '' ? ' - ' . $location_type_id : '' ) . ' es incorrecto o esta inactivo', 
                -1
            );
        }

        //Validar educacion postulante
        $worker_education_id = $input_data['job_worker_education_id'] ?? '';

        $worker_education = $this->db->get_where('tbl_qualifications', [
            'ID' => $worker_education_id,
            'country_id' => 56, // Peru
            'active' => 1
        ])->row();

        if (!$worker_education) {
            throw new \Exception(
                'EL valor del campo job_worker_education_id' . ($worker_education_id != '' ? ' - ' . $worker_education_id : '' ) . ' es incorrecto o esta inactivo', 
                -1
            );
        }

        //Validar meses requeridos
        $experience_months = $input_data['job_worker_experience_months'] ?? '';

        if (!ctype_digit(strval($experience_months))) {
            throw new \Exception(
                'EL valor del campo job_worker_experience_months' . ($experience_months != '' ? ' - ' . $experience_months : '' ) . ' debe ser un número entero', 
                -1
            );
        }

        if ($experience_months < 0) {
            throw new \Exception(
                'EL valor del campo job_worker_experience_months debe ser igual o mayor a 0', 
                -1
            );
        }

        //Validar experiencia en canal
        $experience_channel = $input_data['job_worker_experience_channel'] ?? '';

        if (!in_array($experience_channel, ['1', '0'])) {
            throw new \Exception(
                'EL valor del campo job_worker_experience_channel' . ($experience_channel != '' ? ' - ' . $experience_channel : '' ) . ' debe ser 1 o 0', 
                -1
            );
        }

        //Validar experiencia con el cliente
        $experience_client = $input_data['job_worker_experience_client'] ?? '';

        if (!in_array($experience_client, ['1', '0'])) {
            throw new \Exception(
                'EL valor del campo job_worker_experience_client' . ($experience_client != '' ? ' - ' . $experience_client : '' ) . ' debe ser 1 o 0', 
                -1
            );
        }

        //Validar trabajador debe ser nacional
        $worker_national = $input_data['job_worker_national'] ?? '';

        if (!in_array($worker_national, ['1', '0'])) {
            throw new \Exception(
                'EL valor del campo job_worker_national' . ($worker_national != '' ? ' - ' . $worker_national : '' ) . ' debe ser 1 o 0', 
                -1
            );
        }

        //Validar trabajador debe tener carnet de salud
        $worker_health_carnet = $input_data['job_worker_health_carnet'] ?? '';

        if (!in_array($worker_health_carnet, ['1', '0'])) {
            throw new \Exception(
                'EL valor del campo job_worker_health_carnet' . ($worker_health_carnet != '' ? ' - ' . $worker_health_carnet : '' ) . ' debe ser 1 o 0', 
                -1
            );
        }

        //Validar trabajador debe tener seguro medico
        $worker_health_insurance = $input_data['job_worker_health_insurance'] ?? '';

        if (!in_array($worker_health_insurance, ['1', '0'])) {
            throw new \Exception(
                'EL valor del campo job_worker_health_insurance' . ($worker_health_insurance != '' ? ' - ' . $worker_health_insurance : '' ) . ' debe ser 1 o 0', 
                -1
            );
        }

        //Validar trabajador puede tener discapacidades
        $worker_health_disabilities = $input_data['job_worker_health_disabilities'] ?? '';

        if (!in_array($worker_health_disabilities, ['1', '0'])) {
            throw new \Exception(
                'EL valor del campo job_worker_health_disabilities' . ($worker_health_disabilities != '' ? ' - ' . $worker_health_disabilities : '' ) . ' debe ser 1 o 0', 
                -1
            );
        }

        //Validar Dias laborables
        $job_working_days = $input_data['job_working_days'] ?? [];

        if (!is_array($job_working_days) || count($job_working_days) == 0) {
            throw new \Exception(
                'Debe ingresar por lo menos 1 día laborable en el campo job_working_days, debe ser un listado de números enteros entre 1 y 7', 
                -1
            );
        }

        foreach ($job_working_days as $row_day) {

            $day = $row_day;

            if (!ctype_digit(strval($day)) || $day < 1 || $day > 7) {
                throw new \Exception(
                    'Hay valore(s) en el campo job_working_days que son incorrectos, los dias deben ser números enteros entre 1 y 7', 
                    -1
                );
            }
        }

        //Validar habilidades
        $worker_skills = $input_data['job_worker_skills'] ?? [];

        // if (!is_array($worker_skills) || count($worker_skills) == 0) {
        //     throw new \Exception(
        //         'Debe ingresar por lo menos 1 ID de la habilidad en el campo job_worker_skills, debe ser un listado de IDs', 
        //         -1
        //     );
        // }

        // foreach ($worker_skills as $row_id) {

        //     if (!ctype_digit(strval($row_id))) {
        //         throw new \Exception(
        //             'Hay valore(s) en el campo job_worker_skills que son incorrectos, los id deben ser números enteros', 
        //             -1
        //         );
        //     }
        // }

        // $this->db->from('tbl_skills');
        // $this->db->where_in('id', $worker_skills);
        // $count_worker_skills = $this->db->count_all_results();

        // if ($count_worker_skills != count($worker_skills)) {
        //     throw new \Exception(
        //         'Hay valore(s) en el campo job_worker_skills que son incorrectos, no se encontraron relación con el maestro de habilidades', 
        //         -1
        //     );
        // }

        //Validar experiencias laborales
        $worker_experience = $input_data['job_worker_experience'] ?? [];

        if (!is_array($worker_experience) || count($worker_experience) == 0) {
            throw new \Exception(
                'Debe ingresar por lo menos 1 ID de la experiencia laboral en el campo job_worker_experience, debe ser un listado de IDs', 
                -1
            );
        }

        foreach ($worker_experience as $row_id) {

            if (!ctype_digit(strval($row_id))) {
                throw new \Exception(
                    'Hay valore(s) en el campo job_worker_experience que son incorrectos, los id deben ser números enteros', 
                    -1
                );
            }
        }

        $this->db->from('tbl_job_experiences');
        $this->db->where_in('id', $worker_experience);
        $count_worker_experience = $this->db->count_all_results();

        if ($count_worker_experience != count($worker_experience)) {
            throw new \Exception(
                'Hay valore(s) en el campo job_worker_experience que son incorrectos, no se encontraron relación con el maestro de experiencia laboral', 
                -1
            );
        }
    }

    private function is_valid_date($date)
    {
        if (date('Y-m-d', strtotime($date)) != $date) {
            return false;
        }
        
        $date_part = explode('-', $date);

        if (!checkdate((int)($date_part[1] ?? 0), (int)($date_part[2] ?? 0), (int)($date_part[0] ?? 0))) {
            return false;
        }

        return true;
    }

    private function is_valid_time($time)
    {
        if (date('H:i', strtotime($time)) != $time) {
            return false;
        }
    
        return true;
    }

    public function get_total_vacancies($input_data)
    {
        $vacancies = 0;

        if ($input_data['job_list_type'] == 1) {
             
            $job_locations = $input_data['job_locations'];

            foreach ($job_locations as $job_location)  {
                $vacancies+=$job_location['vacancies'];
            }
        }

        if ($input_data['job_list_type'] == 2) {
             
            $commercial_premises = $input_data['job_commercial_premises'];

            foreach ($commercial_premises as $local_pos)  {
                $vacancies+=$local_pos['vacancies'];
            }
        }

        if ($input_data['job_list_type'] == 3) {
             
            $zones = $input_data['zones'];

            foreach ($zones as $row)  {
                $vacancies+=$row['vacancies'];
            }
        }

        return $vacancies;
    }

    private function send_email_recruiter($request_id = 0)
    {
        $staff_request = $this->Staff_request->find($request_id);

        if (!$staff_request) {
            return false;
        }   

        $job = $this->Posted_job->find(['request_ID' => $staff_request->ID]);

        $url_link = $job && $job->job_slug ? site_url('jobs/' . $job->job_slug) : site_url('login');

        $recruiter = $this->Employer->find($staff_request->recruiter_ID);

        if (!$recruiter) {
            return false;
        }

        $this->load->library('Mail_template/Staff_requests/Staff_request_create');

        $mail_vars = $this->staff_request_create->build([
            'recruiter_name' => $recruiter->first_name,
            'job_title' => $staff_request->job_title,
            'request_id' => $staff_request->ID,
            'url_link' => $url_link
        ]);

        $emails = [
            $recruiter->email
        ];

        $this->email->init();
        $this->email->to($emails);
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }
}
