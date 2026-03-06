<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_external_staff_request extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();

        //Load models
        $this->load->model('Ubigeo');
        $this->load->model('Job_profile');
        $this->load->model('Business_unit');
        $this->load->model('Employer_staff_request_manage_business_unit');
        $this->load->model('Staff_request_type_reason');
        $this->load->model('Job_layout');
        $this->load->model('Expense_type');
        $this->load->model('Exam_emo_type');
        $this->load->model('Screening_type');
        $this->load->model('Exam_covid19_type');

        //Load libraries
        $this->load->library('Notification/Email/notification_email_staff_request_lib');

        $this->ads = $this->Ad->get_ads();
    }

    public function index($request_id = 0)
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Editar solicitud externa - ' . SITE_NAME;  

        $employer = $this->Employer->find($this->session->userdata('user_id'));

        if ($employer->type != 'external' && $employer->type != 'internal-external') {
            $data['msg'] = 'Usted no tiene permisos para crear solicitudes externas por ahora.';
            $this->load->view('page_error_view', $data);
            return;
		}

        $staff_request = $this->Staff_request->find($request_id);

        if (!$staff_request || 
            $staff_request->request_model_id != 2 || 
            $staff_request->sts_process != 'unassigned') {
            show_404();
        }

        $company = $this->Company->find($employer->company_ID);

        $consultants = $this->Employer->get_consultants($employer->ID);
        $clients = $this->Employer->get_clients_company($employer->ID, $staff_request->no_cia, $staff_request->cod_business_unit);
        $cost_centers = $this->Employer->get_cost_centers($employer->ID, $staff_request->no_cia, $staff_request->cod_business_unit, $staff_request->cod_clie);

        $data['company'] = $company;
        $data['request'] = $staff_request;
        $data['recruiter'] = $employer;
        $data['consultants'] = ($consultants['CONSULTORA'] ?? []);
        $data['clients'] = ($clients['CLIENTE'] ?? []);
        $data['business_units'] = $this->Business_unit->all(['active' => '1', 'company_id' => $employer->company_ID]);
        $data['cost_centers'] = ($cost_centers['CENTROCOSTO'] ?? []);
        $data['job_industries'] = $this->Industry->get_industries_actives();
        $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => '1', 'country_id' => $company->country_id]);
        $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
        $data['ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);
        $data['work_experiences'] = $this->Work_experience->all(['active' => '1', 'country_id' => $company->country_id]);
        $data['type_reasons'] = $this->Staff_request_type_reason->all(['active' => 1]);
        $data['expense_types'] = $this->Expense_type->all(['active' => 1]);
        $data['exam_emo_types'] = $this->Exam_emo_type->all(['active' => 1]);
        $data['screening_types'] = $this->Screening_type->all(['active' => 1]);
        $data['exam_covid19_types'] = $this->Exam_covid19_type->all(['active' => 1]);
        $data['job_layouts'] = $this->Job_layout->get_all_by_permission_clients($staff_request->no_cia, $staff_request->cod_clie);
        $data['rys_stages'] = ['2' => 'LONG LIST', '5' => 'SHORT LIST', '6' => 'SELECCIÓN'];
        $data['working_hours'] =  $this->Staff_request->get_working_hours_by_request_id($request_id);
        $data['additional_benefits'] = $this->Staff_request->get_all_additional_benefits_by_request_id($request_id);
		$data['computing_applications'] = $this->Staff_request->get_computing_applications_by_request_id($request_id);
		$data['languages'] = $this->Staff_request->get_languages_by_request_id($request_id);
		$data['job_functions'] = $this->Staff_request->get_job_functions_by_request_id($request_id);
		$data['fixed_competences'] = $this->Staff_request->get_fixed_competences_by_request_id($request_id);
		$data['additional_competences'] = $this->Staff_request->get_additional_competences_by_request_id($request_id);
        $data['request_resource'] =  $this->Staff_request->get_resource_by_request_id($request_id);

        if (!$this->is_valid_data_request() ||
            !$this->is_valid_data_job_descriptions() || 
            !$this->is_valid_data_hiring() ||
            !$this->is_valid_data_job_requeriments() ||
            !$this->is_valid_data_knowledges() ||
            !$this->is_valid_data_functions() ||
            !$this->is_validate_resources()) {

            $this->load->view('employer/staff_request/edit_external_staff_request_view', $data);
            return;
        }
        
        $data_request = [];

        $data_request['data_staff_request'] = $this->build_data_request();
        $data_request['data_working_hours'] = (array)$this->input->post('working_hours');
        $data_request['data_additional_benefits'] = (array)$this->input->post('additional_benefits');
        $data_request['data_computing'] = (array)$this->input->post('computing');
        $data_request['data_languages'] = (array)$this->input->post('languages');
        $data_request['data_job_functions'] = (array)$this->input->post('functions');
        $data_request['data_additional_competences'] = (array)$this->input->post('additional_competences');
        $data_request['data_resources'] = $this->build_data_resources();

        $trans_status = $this->Staff_request->edit_external_staff_request($data_request, $request_id);

        if ($trans_status) {
            $this->session->set_flashdata(
                'success', 
                'La solicitud ha sido editada con éxito'
            );
            $this->notify_edit_staff_request_by_email($employer, $request_id);
            redirect('employer/staff_request/staff_requests/show/' . $request_id);
        } else {
            $this->session->set_flashdata(
                'msg', 
                '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error</strong> La solicitud no se pudo editar, por favor vuelve a intentar.</div>'
            );
        }

        redirect('employer/staff_request/edit_external_staff_request/index/' . $request_id);
    }

    private function is_valid_data_job_descriptions()
    {
        //$this->form_validation->set_rules('job_title', 'Nombre del puesto', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('vacancies', 'N° de vacantes', 'trim|required|is_natural|callback_validate_eecc_vacancies|greater_than[0]');
        $this->form_validation->set_rules('occupational_group', 'Grupo ocupacional', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('industry', 'Área / Departamento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('n_people_reporting', 'N° de personan que reportan', 'trim|is_natural');
        $this->form_validation->set_rules('name_immediate_boss', 'Nombre del jefe inmediato', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('charge_immediate_boss', 'Cargo del jefe inmediato', 'trim|strip_all_tags');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    private function is_valid_data_hiring()
    {
        $reason_request = $this->input->post('reason_request');
        $minimum_salary = trim($this->input->post('minimum_salary'));
     
        $this->form_validation->set_rules('modality_contracting', 'Modalidad de contratación', 'trim|required|in_list[outsourcing,intermediation,direct_form_client,others]');
        $this->form_validation->set_rules('charge_immediate_boss', 'Cargo del jefe inmediato', 'trim|strip_all_tags');      
        $this->form_validation->set_rules('contract_time_qty', 'Cantidad de tiempo de contratación ', 'trim|required|is_natural|greater_than[0]');
        $this->form_validation->set_rules('contract_time_duration', 'Duración del tiempo de contratación', 'trim|required|in_list[año(s),mes(es),día(s)]');
        $this->form_validation->set_rules('reason_request', 'Motivo de requerimiento', 'trim|required|in_list_db[tbl_staff_request_type_reasons.id]');
        $this->form_validation->set_rules('minimum_salary', 'Rango de remuneración mínima', 'trim|numeric|greater_than[0]');        
        //$this->form_validation->set_rules('maximum_salary', 'Rango de remuneración máxima', 'trim|' . ($minimum_salary != '' ? 'required' : '' ) . '|numeric|greater_than_equal_to[' . $minimum_salary . ']');
        $this->form_validation->set_rules('monthly_gross_salary', 'Remuneración bruta mensual', 'trim|required|numeric|callback_validate_monthly_gross_salary|greater_than[0]');
        $this->form_validation->set_rules('job_mode', 'Tipo de jornada laboral', 'trim|required|in_list[full_time,part_time,per_hours,weekends,telecommuting]');
        $this->form_validation->set_rules('workplace', 'Lugar de trabajo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('location', 'Ubicación', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('job_address', 'Dirección de trabajo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('salary_delivery_period', 'Periodo de entrega de sueldo', 'trim|required|in_list[weekly,biweekly,monthly]');
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

    private function is_valid_data_job_requeriments()
    {
        $minimum_age = trim($this->input->post('minimum_age'));
        $maximum_age = trim($this->input->post('maximum_age'));

        $this->form_validation->set_rules('gender', 'Profesión preferentes', 'trim|in_list[male,female,both]');
        $this->form_validation->set_rules('labor_experience_time', 'Tiempo de experiencia mínima laboral', 'trim');        
        $this->form_validation->set_rules('minimum_age', 'Edad mínima', 'trim|' . ($maximum_age != '' ? 'required|' : '') . 'is_natural|greater_than[0]');
        $this->form_validation->set_rules('maximum_age', 'Edad máxima', 'trim|' . ($minimum_age != '' ? 'required|' : '') . 'is_natural|greater_than_equal_to[' . $minimum_age . ']');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
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
        $this->form_validation->set_rules('request_template', 'Layout de puesto', 'trim|required|strip_all_tags');

        $template = $this->input->post('request_template');

        if ($template == '1') {
            $this->form_validation->set_rules('job_profile', 'Perfil de puesto', 'trim|required|strip_all_tags');
        }

        if ($template == '2') {
            $this->form_validation->set_rules('job_layout', 'Layout de puesto', 'trim|required|callback_validate_eecc_job_code|strip_all_tags');
        }

        $this->form_validation->set_rules('cost_center_client', 'Centro de costo cliente', 'trim|max_length[20]|strip_all_tags');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    private function is_valid_data_knowledges()
    {
        $this->form_validation->set_rules('general_knowledges', 'Conocimientos generales', 'trim|strip_all_tags');
        $this->form_validation->set_rules('specific_knowledges', 'Conocimientos específico', 'trim|required|strip_all_tags');
        
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    public function is_valid_data_functions()
    {
        $this->form_validation->set_rules('functions[]', 'Funciones', 'trim|required');     
        $this->form_validation->set_message('required', 'Por favor ingresa al menos 1 función y verifica que no existan campos vacíos');

        return $this->form_validation->run();
    }

    public function validate_step()
    {
        $step = $this->input->post('_step');
        $status = false;

        //let go of the following steps
        if (in_array($step, [4, 8, 9, 11, 12])) {
            $status = true;
        }

        if ($step == 1 && $this->is_valid_data_request()) {
            $status = true;
        }

        if ($step == 2 && $this->is_valid_data_job_descriptions()) {
            $status = true;
        }

        if ($step == 3 && $this->is_valid_data_hiring()) {
            $status = true;
        }

        if ($step == 5 && $this->is_validate_resources()) {
            $status = true;
        }

        if ($step == 6 && $this->is_valid_data_job_requeriments()) {
            $status = true;
        }

        if ($step == 7 && $this->is_valid_data_knowledges()) {
            $status = true;
        }

        if ($step == 10 && $this->is_valid_data_functions()) {
            $status = true;
        }

        echo json_encode([
            'status_validation' => $status,
            'message_errors' => $this->form_validation->error_array()
        ]);
    }

    private function build_data_request()
    {
        $template = $this->input->post('request_template');
        $job_profile_id = trim($this->input->post('job_profile'));
        $job_layout_id = trim($this->input->post('job_layout'));
        
        $job_title = '';

        //Plantilla Perfil laboral
        if ($template == '1') {
            $job_profile = $this->Job_profile->get_job_profile_by_id($job_profile_id);
            $job_title = $job_profile->job_title;
        }

        //Plantilla Layout de puesto
        if ($template == '2') {
            $job_layout = $this->Job_layout->find($job_layout_id);
            $job_title = $job_layout->job_title;
        }

        $replace_employee = $this->input->post('replace_employee') ? $this->input->post('replace_employee') : '-';

        if (count(explode('-', $replace_employee)) == 1) {
            $replace_employee = "-" . $replace_employee;
        }

        list($employee_replaced_dni, $employee_replaced_name) = explode('-', $replace_employee);

        $consultant = explode('|', $this->input->post('consultant_name'));
        $business_unit = explode('|', $this->input->post('business_unit_name'));
        $client_company = explode('|', $this->input->post('client_company_name'));
        $contract_type_model_code = $this->input->post('contract_type_model_code');

        $start_date_work = str_replace('/', '-', $this->input->post('start_date_work'));
        $end_date_work = str_replace('/', '-', $this->input->post('end_date_work'));
        
        $data = [
            'update_date' => date('Y-m-d H:i:s'),
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
            'location' => $this->input->post('location'),
            'working_hours' => $this->input->post('working_hours_manual'),
            'job_address' => $this->input->post('job_address'),
            'employee_replaced_dni' => !empty($employee_replaced_dni) ? $employee_replaced_dni : null,
            'employee_replaced_name' => !empty($employee_replaced_name) ? $employee_replaced_name : null,
            'sts' => 'active',
            'eecc_code' =>  trim((string)$this->input->post('eecc_code')),
            'eecc_description' => trim((string)$this->input->post('eecc_description')),
            'eecc_job_vacancies' => $this->input->post('eecc_job_vacancies') ? $this->input->post('eecc_job_vacancies') : null,
            'eecc_job_code' => trim((string)$this->input->post('eecc_job_code')),
            'eecc_form_id' => trim((string)$this->input->post('eecc_form_id')),
            'charge_ID' => $this->input->post('occupational_group'),
            'industry_ID' => $this->input->post('industry'),
            'wf_area_code' => trim($this->input->post('wf_area_code')) ? trim($this->input->post('wf_area_code')) : null,
            'job_profile_ID' => $template == '1' ? $job_profile_id : null,
            'n_people_reporting' => $this->input->post('n_people_reporting'),
            'modality_contracting' => $this->input->post('modality_contracting'),
            'job_mode' => $this->input->post('job_mode'),
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'workplace' => $this->input->post('workplace'),
            'gender' => $this->input->post('gender'),
            'labor_experience_time' => trim((string)$this->input->post('labor_experience_time')),         
            'minimum_age' => is_numeric($this->input->post('minimum_age')) ? $this->input->post('minimum_age') : null ,
            'maximum_age' => is_numeric($this->input->post('maximum_age')) ? $this->input->post('maximum_age') : null ,
            'general_knowledges' => $this->input->post('general_knowledges'),
            'specific_knowledges' => $this->input->post('specific_knowledges'),
            'additional_comments' => strip_tags(trim($this->input->post('additional_comments'))),
            'contract_type_model_code' => $contract_type_model_code,
            'job_layout_id' => $template == '2' ? $job_layout_id : null,
            'cost_center_client' => $this->input->post('cost_center_client') ?? '',
            'start_date_work' => !empty($start_date_work) ? date('Y-m-d', strtotime($start_date_work)) : null,   
            'end_date_work' => !empty($end_date_work) ? date('Y-m-d', strtotime($end_date_work)) : null,
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

    public function get_job_competences($job_charge_id = 0)
    {
        $competences = $this->Job_competence->get_active_competences_by_job_charge_id($job_charge_id);
        echo json_encode($competences);
    }

    private function notify_edit_staff_request_by_email($obj_recruiter, $request_id)
    {
        $employer_admin = $this->Employer->get_admin_employer_by_company_id($obj_recruiter->company_ID);
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

        $this->email->subject('Solicitud Externa editada');
        $this->email->message($mail_message);     
        $this->email->send();
    }

    public function get_data_job_profiles()
    {
        $user = $this->Employer->find($this->session->userdata('user_id'));

        $consultant = explode('|', $this->input->post('consultant'));
        $business_unit = explode('|', $this->input->post('business_unit'));
        $client_company = explode('|', $this->input->post('client_company'));
        $cost_center = trim($this->input->post('cost_center'));
        
        $result_profiles = $this->Job_profile->get_data_job_profiles(
            $user->company_ID,
            $consultant[0],
            $business_unit[0],
            $client_company[0],
            $cost_center
        );

        echo json_encode(['job_profiles' => $result_profiles]);
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

    public function validate_eecc_vacancies()
    {
        $request_id = $this->input->post('request_id');
        $eecc_code = $this->input->post('eecc_code');
        $eecc_form_id = $this->input->post('eecc_form_id');

        if (!$eecc_code || !$eecc_form_id) {
            return true;
        }

        $eecc_vacancies = (int)$this->input->post('eecc_job_vacancies') ?? 0;
        $vacancies = (int)$this->input->post('vacancies') ?? 0;
        
        $this->db->select([
            'SUM(vacancies) AS total_vacancies'
        ]);
        $this->db->from('tbl_staff_requests');
        $this->db->where('eecc_code', $eecc_code);
        $this->db->where('eecc_form_id', $eecc_form_id);
        $this->db->where('request_model_id', 2);
        $this->db->where('sts', 'active');
        $this->db->where('ID!=', $request_id);
        $this->db->where_in('sts_process', [
            'assigned',
            'pending',
            'published',
            'unassigned'
        ]);

        $sum_row = $this->db->get()->row();

        $total_vacancies = $sum_row->total_vacancies;
        $max_vacancies = $eecc_vacancies - $total_vacancies;
        $max_vacancies = $max_vacancies > 0 ? $max_vacancies : 0;

        if ($max_vacancies == 0) {
            $this->form_validation->set_message('validate_eecc_vacancies', 'La estructura de costo seleccionada para este cargo ya tiene todas las vacantes completas');
            return false;
        }

        if ($vacancies > $max_vacancies) {
            $this->form_validation->set_message('validate_eecc_vacancies', 'El número de vacantes excede el máximo permitido para este cargo, vacantes permitidas: ' . $max_vacancies);
            return false;
        }

        return true;
    }

    public function validate_eecc_job_code()
    {
        $eecc_code = $this->input->post('eecc_code');
        $eecc_form_id = $this->input->post('eecc_form_id');

        if (!$eecc_code || !$eecc_form_id) {
            return true;
        }

        $jl_id = $this->input->post('job_layout');
        $job_layout = $this->Job_layout->find($jl_id);

        if (!$job_layout) {
            $this->form_validation->set_message('validate_eecc_job_code', '{field} no existe');
            return false;
        }

        $eecc_job_code = $this->input->post('eecc_job_code');

        $this->db->from('tbl_job_layouts');
        $this->db->where('code_integration', $eecc_job_code);
        $eecc_job_layout = $this->db->get()->row();

        if (!$eecc_job_layout) {
            $this->form_validation->set_message('validate_eecc_job_code', '{field} incorrecto. Puesto de la estructura de costo no existe en el listado de Layout de puestos.');
            return false;
        }

        if ($job_layout->code_integration != $eecc_job_code) {
            $this->form_validation->set_message('validate_eecc_job_code', '{field} incorrecto, debe seleccionar el puesto ' . $eecc_job_layout->code . ' - ' . $eecc_job_layout->job_title);
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
