<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_external_staff_request extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		$this->load->model('Ubigeo');
        $this->load->model('Job_profile');
    }

    public function e($request_id = 0)
    {
  		$staff_request = $this->Staff_request->get_external_staff_request_by_id(
  			$request_id
  		);  	

  		if (!$staff_request || 
            ($staff_request->sts_process != 'pending' && $staff_request->sts_process != 'unassigned')
        ) {
  			show_404();
  		}

		$data['title'] = "Editar Solicitud Externa - " . SITE_NAME;
        $data['ads_row'] = $this->ads;
		$data['request'] = $staff_request; 
        $data['working_hours'] = $this->Staff_request->get_working_hours_by_request_id($request_id);
		$data['job_charges'] = $this->Job_charge->get_all_active_job_charges();
		$data['job_industries'] = $this->Industry->get_industries_actives();			
		$data['ubigeos'] = $this->Ubigeo->get_all_records();
		$data['additional_benefits'] = $this->Staff_request->get_all_additional_benefits_by_request_id($request_id);
		$data['qualifications'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['computing_applications'] = $this->Staff_request->get_computing_applications_by_request_id($request_id);
		$data['languages'] = $this->Staff_request->get_languages_by_request_id($request_id);
		$data['job_functions'] = $this->Staff_request->get_job_functions_by_request_id($request_id);
		$data['fixed_competences'] = $this->Staff_request->get_fixed_competences_by_request_id($request_id);
		$data['additional_competences'] = $this->Staff_request->get_additional_competences_by_request_id($request_id);
		$data['work_experiences'] = $this->Work_experience->all();
        $data['job_profile'] = $this->Job_profile->find($staff_request->job_profile_ID);

		$this->load->view('employer/staff_request/edit_external_staff_request_view', $data);
    }

    public function save($request_id = 0)
    {
  		$staff_request = $this->Staff_request->get_external_staff_request_by_id(
  			$request_id
  		);  	

  		if (!$staff_request || 
            ($staff_request->sts_process != 'pending' && $staff_request->sts_process != 'unassigned')
        ) {
  			show_404();
  		}

        if (/*!$this->is_valid_data_request() ||*/
            !$this->is_valid_data_job_descriptions() || 
            !$this->is_valid_data_hiring() ||
            !$this->is_valid_data_job_requeriments() ||
            !$this->is_valid_data_knowledges() ||
            !$this->is_valid_data_functions()) {

    		echo json_encode(
    			array(
	    			'success' => false,
	    			'errors' => $this->form_validation->error_array() 
    			)
    		);

            return;
        }

  		$data['data_staff_request'] = $this->build_data_request();
        $data['data_working_hours'] = (array)$this->input->post('working_hours');
        $data['data_external_staff_request'] = $this->build_data_external_request();
        $data['data_additional_benefits'] = (array)$this->input->post('additional_benefits');
        $data['data_computing'] = (array)$this->input->post('computing');
        $data['data_languages'] = (array)$this->input->post('languages');
        $data['data_job_functions'] = (array)$this->input->post('functions');
        $data['data_additional_competences'] = (array)$this->input->post('additional_competences');

        $trans_status = $this->Staff_request->edit_external_staff_request($data, $request_id);
   
        echo json_encode(
        	array(
        		'success' => $trans_status,
        		'request_id' => $request_id
    	    )
    	);
    }

    private function build_data_request()
    {
        /*
        $job_profile_id = trim($this->input->post('job_profile'));
        $job_profile = $this->Job_profile->get_job_profile_by_id($job_profile_id);
        */
        $replace_employee = $this->input->post('replace_employee') ? $this->input->post('replace_employee') : '-';

        list($employee_replaced_dni, $employee_replaced_name) = explode('-', $replace_employee);

        $data = [
            //'type_requirement' => $this->input->post('type_requirement'),
            //'job_title' => $job_profile->job_title,
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
            'employee_replaced_dni' => !empty($employee_replaced_dni) ? $employee_replaced_dni : null,
            'employee_replaced_name' => !empty($employee_replaced_name) ? $employee_replaced_name : null,
        ];

        return $data;
    }

    private function is_valid_data_job_descriptions()
    {
        //$this->form_validation->set_rules('job_title', 'Nombre del puesto', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('vacancies', 'N° de vacantes', 'trim|required|is_natural|greater_than[0]');
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
        $this->form_validation->set_rules('reason_request', 'Motivo de requerimiento', 'trim|required|in_list[replacement,new,vacations,license]');
        $this->form_validation->set_rules('minimum_salary', 'Rango de remuneración mínima', 'trim|numeric|greater_than[0]');        
        $this->form_validation->set_rules('maximum_salary', 'Rango de remuneración máxima', 'trim|' . ($minimum_salary != '' ? 'required' : '' ) . '|numeric|greater_than_equal_to[' . $minimum_salary . ']');
        $this->form_validation->set_rules('monthly_gross_salary', 'Remuneración bruta mensual ', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('job_mode', 'Tipo de jornada laboral', 'trim|required|in_list[full_time,part_time,per_hours,weekends,telecommuting]');
        $this->form_validation->set_rules('workplace', 'Lugar de trabajo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('location', 'Ubicación', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('job_address', 'Dirección de trabajo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('salary_delivery_period', 'Periodo de entrega de sueldo', 'trim|required|in_list[weekly,biweekly,monthly]');
        $this->form_validation->set_rules('household_allowance', 'Asignación familiar', 'trim|required|in_list[yes,no]');
        $this->form_validation->set_rules('working_hours_manual', 'Horario laboral', 'trim');
        $this->form_validation->set_rules('field_working_hours', 'Horario laboral', 'callback_validate_working_hours');
      
        if ($reason_request == 'replacement' || 
            $reason_request == 'vacations' || 
            $reason_request == 'license') {
            $this->form_validation->set_rules('replace_employee', 'Trabajador a reemplazar', 'trim|required|strip_all_tags');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    private function is_valid_data_job_requeriments()
    {
        $minimum_age = trim($this->input->post('minimum_age'));
        $maximum_age = trim($this->input->post('maximum_age'));

        //$this->form_validation->set_rules('level_education', 'Nivel de educación', 'trim|required|strip_all_tags');
        //$this->form_validation->set_rules('preferred_profession', 'Profesión preferentes', 'trim|required|strip_all_tags');
        //$this->form_validation->set_rules('related_profession', 'Profesión afines', 'trim|strip_all_tags');
        //$this->form_validation->set_rules('previous_experience', 'Experiencia previa', 'trim|strip_all_tags');
        $this->form_validation->set_rules('gender', 'Profesión preferentes', 'trim|in_list[male,female,both]');
        //$this->form_validation->set_rules('specialization_or_diploma', 'Especialización/Diplomado', 'trim|strip_all_tags');
        //$this->form_validation->set_rules('position_experience_time', 'Tiempo de experiencia mínima en el pusto', 'trim|required|in_list[fresh,<1,3m,6m,1,2,3,4,5,6,7,8,9,10,10+]');
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

    public function build_data_external_request()
    {
        $start_hour_lunch = strtotime($this->input->post('start_hour_lunch') . ' '. $this->input->post('start_hour_lunch_abr'));
        $end_hour_lunch = strtotime($this->input->post('end_hour_lunch') . ' '. $this->input->post('end_hour_lunch_abr'));

        $job_profile_id = trim($this->input->post('job_profile'));
        $job_profile = $this->Job_profile->get_job_profile_by_id($job_profile_id);
        $level_education = $this->Qualification->get_record_by_id($job_profile->study_grade_req);
        $specialization_or_diploma = $this->Qualification->get_record_by_id($job_profile->study_grade_min);
        $level_education = $level_education['text'];
        $specialization_or_diploma = $specialization_or_diploma['text'];

        $data = [
            'n_people_reporting' => $this->input->post('n_people_reporting'),
            'charge_ID' => $this->input->post('occupational_group'),
            'industry_ID' => $this->input->post('industry'),
            'modality_contracting' => $this->input->post('modality_contracting'),
            'job_mode' => $this->input->post('job_mode'),
            'start_hour_lunch' => $start_hour_lunch && $end_hour_lunch ? date('H:i', $start_hour_lunch) : null,
            'end_hour_lunch' => $start_hour_lunch && $end_hour_lunch ? date('H:i', $end_hour_lunch) : null,
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'workplace' => $this->input->post('workplace'),
            'household_allowance' => $this->input->post('household_allowance'),
            'preferred_profession' => $job_profile->education_req_detail,
            //'related_profession' => $this->input->post('related_profession'),
            'level_education' => $level_education,
            'previous_experience' => $job_profile->experience_detail,
            'gender' => $this->input->post('gender'),
            'specialization_or_diploma' => $specialization_or_diploma,
            'position_experience_time' => $job_profile->experience,
            'labor_experience_time' => $this->input->post('labor_experience_time'),         
            'minimum_age' => is_numeric($this->input->post('minimum_age')) ? $this->input->post('minimum_age') : null ,
            'maximum_age' => is_numeric($this->input->post('maximum_age')) ? $this->input->post('maximum_age') : null ,
            'general_knowledges' => $this->input->post('general_knowledges'),
            'specific_knowledges' => $this->input->post('specific_knowledges'),
            'additional_comments' => strip_tags(trim($this->input->post('additional_comments'))),
            'job_profile_ID' => $job_profile_id
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
}
