<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile_survey_internal extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		$this->load->model('Mof');
		$this->load->model('Ubigeo');
        $this->load->model('Internal_area');
        $this->load->model('Staff_request_authority');
        $this->load->model('Staff_request_authoritation');
        $this->load->model('Staff_request_type_reason');
    }

    public function index($request_id)
    {
        $staff_request = $this->Staff_request->get_internal_staff_request_by_id(
            $request_id
        );  	

        $data['ads_row'] = $this->ads;
		$data['title'] = "Guardar Levantamiento de Perfil - " . SITE_NAME;
		$data['staff_request'] = $staff_request; 
		$data['working_hours'] = $this->Staff_request->get_working_hours_by_request_id($request_id);
		$data['internal_areas'] = $this->Internal_area->get_all_active_areas();
		$data['ubigeos'] = $this->Ubigeo->get_all_records();
		$data['mofs'] = $this->Mof->get_mofs_by_belonging_area_id($staff_request->belonging_area_ID);
		$data['mof'] = $this->Mof->find($staff_request->mof_ID);
		$data['additional_benefits'] = $this->Staff_request->get_all_additional_benefits_by_request_id($request_id);
        $data['type_reasons'] = $this->Staff_request_type_reason->all(['active' => 1]);
        
		$this->load->view('employer/staff_request/profile_survey/profile_survey_internal', $data);
    }

    public function save($request_id = 0)
    {
        $this->load->library(
            'App/Staff_requests/Staff_requests_profile_survey_internal', 
            null, 
            'Staff_requests_profile_survey_internal'
        );

		$staff_request = $this->Staff_request->get_internal_staff_request_by_id(
  			$request_id
  		);  	

    	if ($this->validate_data_staff_request() !== true) {
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
        $data['data_internal_staff_request'] = $this->build_data_internal_request();
        $data['data_working_hours'] = (array)$this->input->post('working_hours');
        $data['data_additional_benefits'] = (array)$this->input->post('additional_benefits');

        $trans_status = $this->Staff_requests_profile_survey_internal->save(
        	$data, 
        	$request_id
        );

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
            'type_requirement' => $this->input->post('type_requirement'),
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
            'employee_replaced_dni' => $this->input->post('reason_request') != 'new' ? $employee_replaced_dni : null,
            'employee_replaced_name' => $this->input->post('reason_request') != 'new' ? $employee_replaced_name : null,
            'user_management' => $this->input->post('user_management'),
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'applicant_headquarter' => $this->input->post('applicant_headquarter'),
            'start_date_work' => !empty($start_date_work) ? date('Y-m-d', strtotime($start_date_work)) : null,
            'renovable' => $this->input->post('renovable'),
            'observations' => $this->input->post('observations'),
            'employee_change_file_path' => $this->input->post('reason_request') != 'new' ? $this->input->post('attached_file') : null,
            'start_date_work' => !empty($start_date_work) ? date('Y-m-d', strtotime($start_date_work)) : null,   
            'end_date_work' => !empty($end_date_work) ? date('Y-m-d', strtotime($end_date_work)) : null
        ];

        return $data;
    }

    private function build_data_internal_request()
    {
        $data = [
            'user_management' => $this->input->post('user_management'),
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'applicant_headquarter' => $this->input->post('applicant_headquarter'),
            'renovable' => $this->input->post('renovable'),
            'observations' => $this->input->post('observations'),
            'employee_change_file_path' => $this->input->post('reason_request') != 'new' ? $this->input->post('attached_file') : null
        ];

        return $data;
    }

	private function validate_data_staff_request()
	{
		//Validate data job description
		$this->form_validation->set_rules('user_management', 'Gerencia usuaria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('applicant_headquarter', 'Jefatura del solicitante', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('vacancies', 'N° de vacantes', 'trim|required|is_natural|greater_than[0]');
		$this->form_validation->set_rules('name_immediate_boss', 'Nombre del jefe inmediato', 'trim|required|strip_all_tags');

		//Validate data hiring
		$reason_request = $this->input->post('reason_request');
		$minimum_salary = trim($this->input->post('minimum_salary'));

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
}
