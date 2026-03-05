<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile_survey_external extends CI_Controller
{	
    public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		$this->load->model('Ubigeo');
        $this->load->model('Job_profile');
        $this->load->model('Staff_request_type_reason');
    }

    public function index($request_id = 0)
    {
  		$staff_request = $this->Staff_request->get_external_staff_request_by_id(
  			$request_id
  		);  	

        $company = $this->Company->find($staff_request->company_ID);

		$data['title'] = "Levantamiento de Perfil Externa - " . SITE_NAME;
        $data['ads_row'] = $this->ads;
		$data['request'] = $staff_request; 
        $data['working_hours'] = $this->Staff_request->get_working_hours_by_request_id($request_id);
		$data['job_charges'] =  $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
		$data['job_industries'] = $this->Industry->get_industries_actives();			
		$data['ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);
		$data['additional_benefits'] = $this->Staff_request->get_all_additional_benefits_by_request_id($request_id);
		$data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => '1', 'country_id' => $company->country_id]);
		$data['computing_applications'] = $this->Staff_request->get_computing_applications_by_request_id($request_id);
		$data['languages'] = $this->Staff_request->get_languages_by_request_id($request_id);
		$data['job_functions'] = $this->Staff_request->get_job_functions_by_request_id($request_id);
		$data['fixed_competences'] = $this->Staff_request->get_fixed_competences_by_request_id($request_id);
		$data['additional_competences'] = $this->Staff_request->get_additional_competences_by_request_id($request_id);
		$data['work_experiences'] = $this->Work_experience->all();
        $data['job_profile'] = $this->Job_profile->find($staff_request->job_profile_ID);
        $data['company'] = $company;
        $data['type_reasons'] = $this->Staff_request_type_reason->all(['active' => 1]);

        $this->load->view('employer/staff_request/profile_survey/profile_survey_external', $data);
    }

    public function save($request_id = 0)
    {
        $this->load->library(
            'App/Staff_requests/Staff_requests_profile_survey_external', 
            null, 
            'Staff_requests_profile_survey_external'
        );

  		$staff_request = $this->Staff_request->get_external_staff_request_by_id(
  			$request_id
  		);  	

        if (!$this->is_valid_data_job_descriptions() || 
            !$this->is_valid_data_hiring() ||
            !$this->is_valid_data_job_requeriments() ||
            !$this->is_valid_data_knowledges() ||
            !$this->is_valid_data_functions()) {

            echo json_encode([
                'status' => false,
                'message' => 'Hay campos que no cumplen la validación',
                'data' => [
                    'errors' => $this->form_validation->error_array() 
                ]
            ]);

            return;
        }

  		$data['data_staff_request'] = $this->build_data_request();
        $data['data_working_hours'] = (array)$this->input->post('working_hours');
        $data['data_additional_benefits'] = (array)$this->input->post('additional_benefits');
        $data['data_computing'] = (array)$this->input->post('computing');
        $data['data_languages'] = (array)$this->input->post('languages');
        $data['data_job_functions'] = (array)$this->input->post('functions');
        $data['data_additional_competences'] = (array)$this->input->post('additional_competences');

        $trans_status = $this->Staff_requests_profile_survey_external->save($data, $request_id);
   
        if ($trans_status) {
            $this->session->set_flashdata(
                'success', 
                'Levantamiento de perfil realizado'
            );

            echo json_encode([
                'status' => $trans_status,
                'message' => 'OK'
            ]);
            return;
        }

        if (!$trans_status) {
            echo json_encode([
                'status' => $trans_status,
                'message' => 'Levantamiento de perfil no se pudo realizar'
            ]);
            return;
        }
    }

    private function build_data_request()
    {
        $replace_employee = $this->input->post('replace_employee') ? $this->input->post('replace_employee') : '-';

        list($employee_replaced_dni, $employee_replaced_name) = explode('-', $replace_employee);

        $start_date_work = str_replace('/', '-', $this->input->post('start_date_work'));
        $end_date_work = str_replace('/', '-', $this->input->post('end_date_work'));

        $data = [
            'vacancies' => $this->input->post('vacancies'),
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
            'employee_replaced_dni' => $this->input->post('reason_request') != 'new' ? $employee_replaced_dni : null,
            'employee_replaced_name' => $this->input->post('reason_request') != 'new' ? $employee_replaced_name : null,
            'charge_ID' => $this->input->post('occupational_group'),
            'industry_ID' => $this->input->post('industry'),
            'n_people_reporting' => $this->input->post('n_people_reporting'),
            'modality_contracting' => $this->input->post('modality_contracting'),
            'job_mode' => $this->input->post('job_mode'),
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'workplace' => $this->input->post('workplace'),
            'gender' => $this->input->post('gender'),
            'labor_experience_time' => trim($this->input->post('labor_experience_time')),         
            'minimum_age' => is_numeric($this->input->post('minimum_age')) ? $this->input->post('minimum_age') : null ,
            'maximum_age' => is_numeric($this->input->post('maximum_age')) ? $this->input->post('maximum_age') : null ,
            'general_knowledges' => $this->input->post('general_knowledges'),
            'specific_knowledges' => $this->input->post('specific_knowledges'),
            'additional_comments' => strip_tags(trim($this->input->post('additional_comments'))),
            'start_date_work' => !empty($start_date_work) ? date('Y-m-d', strtotime($start_date_work)) : null,   
            'end_date_work' => !empty($end_date_work) ? date('Y-m-d', strtotime($end_date_work)) : null,
        ];

        return $data;
    }

    private function is_valid_data_job_descriptions()
    {
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
        $this->form_validation->set_rules('maximum_salary', 'Rango de remuneración máxima', 'trim|' . ($minimum_salary != '' ? 'required' : '' ) . '|numeric|greater_than_equal_to[' . $minimum_salary . ']');
        $this->form_validation->set_rules('monthly_gross_salary', 'Remuneración bruta mensual ', 'trim|required|numeric|callback_validate_monthly_gross_salary|greater_than[0]');
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
        $this->form_validation->set_rules('consultant_name', 'Consultora', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('client_company_name', 'Nombre de la empresa cliente', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('business_unit_name', 'Unidad de negocio', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('job_profile', 'Perfil de puesto', 'trim|required|strip_all_tags');

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

        $staff_request = $this->Staff_request->find($request_id);

        if (!$staff_request) {
            $this->form_validation->set_message('validate_eecc_vacancies', 'Error al validar vacantes');
            return false;
        }

        $eecc_code = $staff_request->eecc_code;
        $eecc_form_id = $staff_request->eecc_form_id;

        if (!$eecc_code || !$eecc_form_id) {
            return true;
        }

        $eecc_vacancies = (int)$staff_request->eecc_job_vacancies;
        $vacancies = (int)$this->input->post('vacancies') ?? 0;
        
        $this->db->select([
            'SUM(vacancies) AS total_vacancies'
        ]);
        $this->db->from('tbl_staff_requests');
        $this->db->where('ID!=', $request_id);
        $this->db->where('eecc_code', $eecc_code);
        $this->db->where('eecc_form_id', $eecc_form_id);
        $this->db->where('request_model_id', 2);
        $this->db->where('sts', 'active');
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
}
