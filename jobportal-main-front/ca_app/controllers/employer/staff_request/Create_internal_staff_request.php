<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Create_internal_staff_request extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        //Load models
        $this->load->model('Mof');
        $this->load->model('Internal_area');
        $this->load->model('Staff_request_authority');
        $this->load->model('Staff_request_authoritation');
        $this->load->model('Ubigeo');
        $this->load->model('Business_unit');
        $this->load->model('Staff_request_type_reason');
        $this->load->model('Job_layout');
        $this->load->model('Expense_type');
        $this->load->model('Exam_emo_type');
        $this->load->model('Screening_type');
        $this->load->model('Exam_covid19_type');
    
        //Load libraries
        $this->load->library(
            'Notification/Email/notification_email_staff_request_lib'
        );
    
        $this->ads = $this->Ad->get_ads();
    }

    public function index()
    {
        check_permission_action('staff_requests', 'create_internal');
        
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Crear solicitud interna - ' . SITE_NAME;

        $obj_recruiter = $this->Employer->find($this->session->userdata('user_id'));
    
        $user = $this->Employer->find($this->session->userdata('user_id'));
        $company = $this->Company->find($user->company_ID);

        $data['recruiter'] = $obj_recruiter;
        $data['internal_areas'] = $this->Internal_area->all(['active' => 1, 'country_id' => $company->country_id]);
        $data['additional_benefits'] = $this->Laboral_benefit->get_all_records();
        $data['authorities2'] = $this->Staff_request_authority->get_active_authorities_by_type($user->company_ID, 2);
        $data['authorities3'] = $this->Staff_request_authority->get_active_authorities_by_type($user->company_ID, 3);
        $data['authorities4'] = $this->Staff_request_authority->get_active_authorities_by_type($user->company_ID, 4);
        $data['ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);
        $data['business_units'] =  $this->Business_unit->all(['active' => 1, 'company_id' => $user->company_ID]); 
        $data['country'] = $this->Country->find($company->country_id);
        $data['type_reasons'] = $this->Staff_request_type_reason->all(['active' => 1]);
        $data['expense_types'] = $this->Expense_type->all(['active' => 1]);
        $data['exam_emo_types'] = $this->Exam_emo_type->all(['active' => 1]);
        $data['screening_types'] = $this->Screening_type->all(['active' => 1]);
        $data['exam_covid19_types'] = $this->Exam_covid19_type->all(['active' => 1]);

        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        if (!$this->is_valid_data_request() ||
            !$this->is_valid_data_area_mof() ||
            !$this->is_valid_data_job_descriptions() ||
            !$this->is_valid_data_hiring() ||
            !$this->is_valid_data_authorities() ||
            !$this->is_validate_resources()) {

            $this->load->view('employer/staff_request/create_internal_staff_request_view', $data);
            return;
        }

        $data['data_staff_request'] = $this->build_data_request($obj_recruiter);
        $data['data_working_hours'] = (array)$this->input->post('working_hours');
        $data['data_additional_benefits'] = (array)$this->input->post('additional_benefits');
        $data['data_authorities'] = (array)$this->input->post('authorities');
        $data['data_resources'] = $this->build_data_resources();
        
        $request_id = $this->Staff_request->create_internal_staff_request($data);

        if ($request_id) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> La solicitud se ha creado con éxito.</div>');
            $this->send_email_to_authorities($request_id);
            $this->notification_email_staff_request_lib->notify_creation($request_id);

            redirect('employer/staff_request/my_staff_requests');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error </strong> La solicitud no se pudo crear, por favor vuelve a intentar.</div>');
        }
           
        redirect('employer/staff_request/create_internal_staff_request');
    }

    public function validate_step()
    {
        $step = $this->input->post('_step');
        $status = false;

        //Saltar paso 5
        if (in_array($step, [5])) {
            $status = true;
        }

        if ($step == 1 && $this->is_valid_data_request()) {
            $status = true;
        }

        if ($step == 2 && $this->is_valid_data_area_mof()) {
            $status = true;
        }
    
        if ($step == 3 && $this->is_valid_data_job_descriptions()) {
            $status = true;
        }

        if ($step == 4 && $this->is_valid_data_hiring()) {
            $status = true;
        }

        if ($step == 6 && $this->is_validate_resources()) {
            $status = true;
        }

        if ($step == 7 && $this->is_valid_data_authorities()) {
            $status = true;
        }

        echo json_encode([
            'status_validation' => $status,
            'message_errors' => $this->form_validation->error_array()
        ]);
    }

    public function is_valid_data_request()
    {
        $this->form_validation->set_rules('type_requirement', 'Tipo de requerimiento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('type_expense', 'Tipo de egreso', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('consultant_name', 'Consultora', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('client_company_name', 'Nombre de la empresa cliente', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('business_unit_name', 'Unidad de negocio', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('contract_type_model_code', 'Contrato Tipo', 'trim|strip_all_tags');
        $this->form_validation->set_rules('cost_center_client', 'Centro de costo cliente', 'trim|max_length[20]|strip_all_tags');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    private function is_valid_data_area_mof()
    {
        $this->form_validation->set_rules('internal_area', 'Área perteneciente', 'trim|required');
        $this->form_validation->set_rules('request_template', 'Plantilla', 'trim|required');
        
        $template = $this->input->post('request_template');

        if ($template == '1') {
            $this->form_validation->set_rules('select_mof', 'MOF', 'trim|required');
        }

        if ($template == '2') {
            $this->form_validation->set_rules('job_layout', 'Layout de puesto', 'trim|required');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');
        return $this->form_validation->run();
    }

    private function is_valid_data_job_descriptions()
    {
        $this->form_validation->set_rules('user_management', 'Gerencia usuaria', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('applicant_headquarter', 'Jefatura del solicitante', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('vacancies', 'N° de vacantes', 'trim|required|is_natural|greater_than[0]');
        $this->form_validation->set_rules('name_immediate_boss', 'Nombre del jefe inmediato', 'trim|required|strip_all_tags');
    
        $this->form_validation->set_message('required', 'El campo %s es requerido');
        return $this->form_validation->run();
    }

    private function is_valid_data_hiring()
    {
        $reason_request = $this->input->post('reason_request');
        $minimum_salary = trim($this->input->post('minimum_salary'));
    
        //$this->form_validation->set_rules('type_contracting', 'Tipo de contratación', 'trim|required|in_list[temporary,fixed_term]');
        $this->form_validation->set_rules('charge_immediate_boss', 'Cargo del jefe inmediato', 'trim|strip_all_tags');      
        $this->form_validation->set_rules('contract_time_qty', 'Tiempo de contratación ', 'trim|required|is_natural|greater_than[0]');
        $this->form_validation->set_rules('contract_time_duration', 'Duración del tiempo de conratación', 'trim|required|in_list[año(s),mes(es),día(s)]');
        $this->form_validation->set_rules('renovable', 'Renovable', 'trim|required|in_list[yes,no]');
        $this->form_validation->set_rules('reason_request', 'Motivo de requerimiento', 'trim|required|in_list_db[tbl_staff_request_type_reasons.id]');
        $this->form_validation->set_rules('minimum_salary', 'Rango de remuneración mínima', 'trim|numeric|greater_than[0]');      
        $this->form_validation->set_rules('maximum_salary', 'Rango de remuneración máxima', 'trim|' . ($minimum_salary != '' ? 'required|' : '' ) . 'numeric|greater_than_equal_to[' . $minimum_salary . ']');
        $this->form_validation->set_rules('monthly_gross_salary', 'Remuneración bruta mensual ', 'trim|required|numeric|callback_validate_monthly_gross_salary|greater_than[0]');
        $this->form_validation->set_rules('type_remuneration', 'Tipo de remuneración', 'trim|in_list[1,2,3]');
        $this->form_validation->set_rules('salary_delivery_period', 'Periodo de entrega de sueldo', 'trim|required|in_list[weekly,biweekly,monthly]');
        $this->form_validation->set_rules('location', 'Ubicación', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('job_address', 'Dirección de trabajo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('working_hours_manual', 'Horario laboral', 'trim');
        $this->form_validation->set_rules('field_working_hours', 'Horario laboral', 'callback_validate_working_hours');
        
        if ($reason_request == 'replacement' || 
            $reason_request == 'vacations' || 
            $reason_request == 'license') {
            $this->form_validation->set_rules('replace_employee', 'Trabajador a reemplazar', 'trim|required|strip_all_tags');
        }

        $this->form_validation->set_rules('start_date_work', 'Fecha inicio contrato', 'trim|valid_date');

        $start_date_work = $this->input->post('start_date_work');
        if ($start_date_work) {
            $this->form_validation->set_rules(
                'end_date_work', 
                'Fecha fin contrato', 
                'trim|required|valid_date|date_greater_than_equal_to[' . $start_date_work . ']',
                ['date_greater_than_equal_to' => 'La fecha de fin contrato debe ser mayor o igual a la fecha de inicio contrato']
            );
        }
    
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    private function is_valid_data_authorities()
    {
        $this->form_validation->set_rules('authorities[1][]', 'Autoridad', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('authorities[2]', 'Autoridad', 'trim|required|strip_all_tags');       
        $this->form_validation->set_rules('authorities[3]', 'Autoridad', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('authorities[4]', 'Autoridad', 'trim|required|strip_all_tags');

        return $this->form_validation->run();
    }

    private function send_email_to_authorities($request_id)
    {
        $authorities = $this->Staff_request_authoritation->get_request_authorities_by_request(
            $request_id
        );
    
        foreach ($authorities as $authority) {          

            $data_email = [
                'name' => $authority->personal_name,
                'url_link' => site_url('general/authorities/staff_requests/show?t=' . $authority->token)  
            ];

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($authority->personal_email);

            $mail_message = load_email_view('email/authorize_request', $data_email);

            $this->email->subject('Autorización requerida - Solicitud de personal');
            $this->email->message($mail_message);     
            $this->email->send();
        }
    }

   private function build_data_request($obj_recruiter)
   {
        $template = $this->input->post('request_template');
        $mof_id = trim($this->input->post('select_mof'));
        $job_layout_id = trim($this->input->post('job_layout'));

        $job_title = '';

        //Plantilla MOF
        if ($template == '1') {
            $mof = $this->Mof->find($mof_id);
            $job_title = $mof->job_title;
        }

        //Plantilla Layout de puesto
        if ($template == '2') {
            $job_layout = $this->Job_layout->find($job_layout_id);
            $job_title = $job_layout->job_title;
        }

        $start_date_work = str_replace('/', '-', $this->input->post('start_date_work'));
        $end_date_work = str_replace('/', '-', $this->input->post('end_date_work'));
        $replace_employee =  $this->input->post('replace_employee') ?  $this->input->post('replace_employee') : '-';

        if (count(explode('-', $replace_employee)) == 1) {
            $replace_employee = "-" . $replace_employee;
        }

        list($employee_replaced_dni, $employee_replaced_name) = explode('-', $replace_employee);

        $consultant = explode('|', $this->input->post('consultant_name'));
        $business_unit = explode('|', $this->input->post('business_unit_name'));
        $client_company = explode('|', $this->input->post('client_company_name'));
        $contract_type_model_code = $this->input->post('contract_type_model_code');

        $data = [
            'creation_date' => date('Y-m-d H:i:s'),
            'request_type' => 'internal',
            'no_cia' => $consultant[0],
            'consultant_name' => $consultant[1],
            'cod_clie' => $client_company[0],
            'client_company_name' => $client_company[1],
            'cod_business_unit' => $business_unit[0],
            'business_unit_name' => $business_unit[1],
            'cost_center' => $this->input->post('cost_center'),
            'job_title' => $job_title,
            'vacancies' => $this->input->post('vacancies'),
            'type_requirement' => $this->input->post('type_requirement'),
            'type_expense' => $this->input->post('type_expense'),
            'name_immediate_boss' => $this->input->post('name_immediate_boss'),
            'charge_immediate_boss' => $this->input->post('charge_immediate_boss'),
            'contract_time_qty' => is_numeric($this->input->post('contract_time_qty')) ? $this->input->post('contract_time_qty') : null,
            'contract_time_duration' => is_numeric($this->input->post('contract_time_qty')) ? $this->input->post('contract_time_duration') : null,
            'reason_request' => $this->input->post('reason_request'),
            'minimum_salary' => is_numeric($this->input->post('minimum_salary')) ? $this->input->post('minimum_salary') : null,
            'maximum_salary' => is_numeric($this->input->post('maximum_salary')) ? $this->input->post('maximum_salary') : null,
            'monthly_gross_salary' => is_numeric($this->input->post('monthly_gross_salary')) ? $this->input->post('monthly_gross_salary') : null,
            'type_remuneration' => $this->input->post('type_remuneration') ? $this->input->post('type_remuneration') : null,
            'location' => $this->input->post('location'),
            'working_hours' => $this->input->post('working_hours_manual'),
            'job_address' => $this->input->post('job_address'),
            'employee_replaced_dni' => !empty($employee_replaced_dni) ? $employee_replaced_dni : null,
            'employee_replaced_name' => !empty($employee_replaced_name) ? $employee_replaced_name : null,
            'sts' => 'active',
            'sts_process' => 'pending',
            'company_ID' => $obj_recruiter->company_ID,
            'recruiter_ID' => $obj_recruiter->ID,
            'request_model_id' => 1,
            'user_management' => $this->input->post('user_management'),
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'applicant_headquarter' => $this->input->post('applicant_headquarter'),
            'start_date_work' => !empty($start_date_work) ? date('Y-m-d', strtotime($start_date_work)) : null,   
            'end_date_work' => !empty($end_date_work) ? date('Y-m-d', strtotime($end_date_work)) : null,            
            'renovable' => $this->input->post('renovable'),
            'observations' => $this->input->post('observations'),
            'employee_change_file_path' => $this->input->post('reason_request') != 'new' ? $this->input->post('attached_file') : null,
            'belonging_area_ID' => $this->input->post('internal_area'),
            'mof_ID' => $template == '1' ? $mof_id : null,
            'job_layout_id' => $template == '2' ? $job_layout_id : null,
            'contract_type_model_code' => $contract_type_model_code,
            'cost_center_client' => $this->input->post('cost_center_client') ?? ''
        ];

        return $data;
    }

    public function build_data_resources()
    {
        $input = $this->input->post();

        $data = [
            'emo_type_id' => $input['emo_type'],
            'emo_protocol_detail' => $input['emo_type'] != 0 ? $input['emo_protocol_detail'] : '',
            'emo_expense_type' => $input['emo_type'] != 0 ? $input['emo_expense_type'] : '',
            'emo_staff_charge' => $input['emo_type'] != 0 ? $input['emo_staff_charge'] : '',
            'screening_type_id' => $input['screening_type'],
            'screening_expense_type' => $input['screening_type'] != 0 ? $input['screening_expense_type'] : '',
            'screening_perform_stage' => $input['screening_type'] != 0 ? $input['screening_perform_stage'] : null,
            'screening_staff_charge' => $input['screening_type'] != 0 ? $input['screening_staff_charge'] : '',
            'covid19_type_id' => $input['covid19_type'],
            'covid19_expense_type' => $input['covid19_type'] != 0 ? $input['covid19_expense_type'] : '',
            'covid19_perform_stage' => $input['covid19_type'] != 0 ? $input['covid19_perform_stage'] : null,
            'covid19_staff_charge' => $input['covid19_type'] != 0 ? $input['covid19_staff_charge'] : '',
            'exam_complementary' => $input['exam_complementary_type'],
            'exam_complementary_staff_charge' => $input['exam_complementary_type'] != 'No aplica' ? $input['exam_complementary_staff_charge'] : '',
            'verify_home' => $input['verify_home'],
            'verify_home_perform_stage' => $input['verify_home'] != 0 ? $input['verify_home_perform_stage'] : null,
            'verify_home_staff_charge' => $input['verify_home'] != 0 ? $input['verify_home_staff_charge'] : '',
            'verify_credit' => $input['verify_credit'],
            'verify_credit_perform_stage' => $input['verify_credit'] != 0 ? $input['verify_credit_perform_stage'] : null,
            'verify_credit_staff_charge' => $input['verify_credit'] != 0 ? $input['verify_credit_staff_charge'] : '',
            'verify_labor' => $input['verify_labor'],
            'verify_labor_perform_stage' => $input['verify_labor'] != 0 ? $input['verify_labor_perform_stage'] : null,
            'verify_labor_staff_charge' => $input['verify_labor'] != 0 ? $input['verify_labor_staff_charge'] : '',
            'verify_degree' => $input['verify_degree'],
            'verify_degree_perform_stage' => $input['verify_degree'] != 0 ? $input['verify_degree_perform_stage'] : null,
            'verify_degree_staff_charge' => $input['verify_degree'] != 0 ? $input['verify_degree_staff_charge'] : '',
            'verify_degree_person' => $input['verify_degree_person'],
            'verify_degree_person_perform_stage' => $input['verify_degree_person'] != 0 ? $input['verify_degree_person_perform_stage'] : null,
            'verify_degree_person_staff_charge' => $input['verify_degree_person'] != 0 ? $input['verify_degree_person_staff_charge'] : '',
        ];

        return $data;
    }

    public function validate_working_hours()
    {
        $data = (array)$this->input->post('working_hours');

        $this->form_validation->set_message('validate_working_hours', 'Por favor debe seleccionar o detallar un Horario Laboral válido');
            
        if (count($data) == 0 && 
            trim($this->input->post('working_hours_manual')) == '') {
            return false;
        }

        if (count($data) == 0 && 
            trim($this->input->post('working_hours_manual')) != '') {
            return true;
        }

        foreach ($data as $key => $row) {
            if (empty($row['start_day']) ||
                empty($row['end_day']) ||
                empty($row['start_time']) || 
                empty($row['end_time'])) {
                return false;
            }
        }

        return true;
    }

    public function validate_monthly_gross_salary()
    {
        $minimum_salary = ((float)trim($this->input->post('minimum_salary')));
        $maximum_salary = ((float)trim($this->input->post('maximum_salary')));

        if ($minimum_salary <= 0 || $maximum_salary <= 0) {
            return true;
        }
        
        $monthly_gross_salary = ((float)trim($this->input->post('monthly_gross_salary')));

        if ($monthly_gross_salary < $minimum_salary || $monthly_gross_salary > $maximum_salary) {
            $this->form_validation->set_message('validate_monthly_gross_salary', 'Por favor debe ingresar un monto dentro del rango de remuneración ' . number_format($minimum_salary, 2, '.', '') . ' al ' . number_format($maximum_salary, 2, '.', ''));
            return false;
        }

        return true;
    }

    public function is_validate_resources()
    {
        //Validar EMO
        $this->form_validation->set_rules('emo_type', 'EMO Tipo', 'trim|required|strip_all_tags');      

        $emo_type = $this->input->post('emo_type');

        if ($emo_type != '0') {

            $this->form_validation->set_rules('emo_expense_type', 'Tipo egreso', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('emo_staff_charge', 'Emo encargado', 'trim|strip_all_tags');

            if ($emo_type == '10') {
                $this->form_validation->set_rules('emo_protocol_detail', 'Protocolo detalle', 'trim|required|strip_all_tags');
            } else {
                $this->form_validation->set_rules('emo_protocol_detail', 'Protocolo detalle', 'trim|strip_all_tags');
            }
        }

        //Validar Screening
        $this->form_validation->set_rules('screening_type', 'Screening Tipo', 'trim|required|strip_all_tags');

        $screening_type = $this->input->post('screening_type');

        if ($screening_type != '0') {
            $this->form_validation->set_rules('screening_expense_type', 'Tipo egreso', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('screening_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('screening_staff_charge', 'Emo encargado', 'trim|strip_all_tags');
        }

        //Validar Covid19
        $this->form_validation->set_rules('covid19_type', 'Covid Tipo', 'trim|required|strip_all_tags');

        $covid19_type = $this->input->post('covid19_type');

        if ($covid19_type != '0') {
            $this->form_validation->set_rules('covid19_expense_type', 'Tipo egreso', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('covid19_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('covid19_staff_charge', 'Covid encargado', 'trim|strip_all_tags');
        }

        //Validar Emxam. Complementarios
        $this->form_validation->set_rules('exam_complementary_type', 'Exam. complementario', 'trim|required|strip_all_tags');
        $exam_complementary_type = $this->input->post('exam_complementary_type');

        if ($exam_complementary_type != 'No aplica') {
            $this->form_validation->set_rules('exam_complementary_staff_charge', 'Encargado', 'trim|strip_all_tags');
        }

        //Validacion verificacion  domiciliaria
        $this->form_validation->set_rules('verify_home', 'Verificacion domiciliaria', 'trim|required|strip_all_tags');
        $verification_home = $this->input->post('verify_home');

        if ($verification_home != '0') {
            $this->form_validation->set_rules('verify_home_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('verify_home_staff_charge', 'Encargado', 'trim|strip_all_tags');
        }

        //Validacion verificacion crediticia
        $this->form_validation->set_rules('verify_credit', 'Verificacion crediticia', 'trim|required|strip_all_tags');
        $verification_credit = $this->input->post('verify_credit');

        if ($verification_credit != '0') {
            $this->form_validation->set_rules('verify_credit_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('verify_credit_staff_charge', 'Encargado', 'trim|strip_all_tags');
        }

        //Validacion verificacion laboral
        $this->form_validation->set_rules('verify_labor', 'Verificacion crediticia', 'trim|required|strip_all_tags');
        $verification_labor = $this->input->post('verify_labor');

        if ($verification_labor != '0') {
            $this->form_validation->set_rules('verify_labor_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('verify_labor_staff_charge', 'Encargado', 'trim|strip_all_tags');
        }

        //Validacion verificacion grados y titulos
        $this->form_validation->set_rules('verify_degree', 'Verificacion grados y titulos', 'trim|required|strip_all_tags');
        $verification_degrees_title = $this->input->post('verify_degree');

        if ($verification_degrees_title != '0') {
            $this->form_validation->set_rules('verify_degree_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('verify_degree_staff_charge', 'Encargado', 'trim|strip_all_tags');
        }

        //Validacion verificacion grados y titulos precencial
        $this->form_validation->set_rules('verify_degree_person', 'Verificacion grados y titulos presencial', 'trim|required|strip_all_tags');
        $verification_degrees_titles_person = $this->input->post('verify_degree_person');

        if ($verification_degrees_titles_person != '0') {
            $this->form_validation->set_rules('verify_degree_person_perform_stage', 'Etapa', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('verify_degree_person_staff_charge', 'Encargado', 'trim|strip_all_tags');
        }
        
        return $this->form_validation->run();
    }
}
