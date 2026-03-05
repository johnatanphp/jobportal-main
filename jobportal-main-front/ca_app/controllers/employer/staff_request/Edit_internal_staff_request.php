<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_internal_staff_request extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		$this->load->model('Mof');
		$this->load->model('Ubigeo');
        $this->load->model('Internal_area');
        $this->load->model('Staff_request_authority');
        $this->load->model('Staff_request_authoritation');
    }

    public function e($request_id = 0)
    {
  		$staff_request = $this->Staff_request->get_internal_staff_request_by_id(
  			$request_id
  		);  	

  		if (!$staff_request || 
            ($staff_request->sts_process != 'pending' && $staff_request->sts_process != 'unassigned')
        ) {
            show_404();
        }
        
        $data['ads_row'] = $this->ads;
  		$data['title'] = "Editar Solicitud Interna - " . SITE_NAME;
    	$data['staff_request'] = $staff_request; 
        $data['working_hours'] = $this->Staff_request->get_working_hours_by_request_id($request_id);
        $data['internal_areas'] = $this->Internal_area->get_all_active_areas();
        $data['ubigeos'] = $this->Ubigeo->get_all_records();
        $data['mofs'] = $this->Mof->get_mofs_by_belonging_area_id($staff_request->belonging_area_ID);
        $data['mof'] = $this->Mof->find($staff_request->mof_ID);
        
        $data['additional_benefits'] = $this->Staff_request->get_all_additional_benefits_by_request_id($request_id);

        $data['request_authorities1'] = $this->Staff_request_authoritation->get_authorization_type_authority(
        	$request_id, 
        	1
        );
        $data['request_authorities2'] = $this->Staff_request_authoritation->get_authorization_type_authority(
        	$request_id, 
        	2
        );
        $data['request_authorities3'] = $this->Staff_request_authoritation->get_authorization_type_authority(
        	$request_id, 
        	3
        );
        $data['request_authorities4'] = $this->Staff_request_authoritation->get_authorization_type_authority(
        	$request_id, 
        	4
        );
       
        $data['authorities2'] = $this->Staff_request_authority->get_active_authorities_by_type(2);
        $data['authorities3'] = $this->Staff_request_authority->get_active_authorities_by_type(3);
        $data['authorities4'] = $this->Staff_request_authority->get_active_authorities_by_type(4);        

    	$this->load->view('employer/staff_request/edit_internal_staff_request_view', $data);
    }

    public function save($request_id = 0)
    {
		$staff_request = $this->Staff_request->get_internal_staff_request_by_id(
  			$request_id
  		);  	

  		if (!$staff_request || 
            ($staff_request->sts_process != 'pending' && $staff_request->sts_process != 'unassigned')
        ) {
            show_404();
        }

    	if ($this->validate_data_staff_request() !== true) {

            echo json_encode([
                'success' => false,
                'errors' => $this->form_validation->error_array() 
            ]);

            return;
    	}

    	$data['data_staff_request'] = $this->build_data_request();
        $data['data_internal_staff_request'] = $this->build_data_internal_request();
        $data['data_working_hours'] = (array)$this->input->post('working_hours');
        $data['data_additional_benefits'] = (array)$this->input->post('additional_benefits');
        $data['data_authorities'] = (array)$this->input->post('authorities');

        $trans_status = $this->Staff_request->edit_internal_staff_request(
        	$data, 
        	$request_id
        );

        echo json_encode([
            'success' => $trans_status,
            'request_id' => $request_id
        ]);
    }

    private function build_data_request()
    {
        $mof_id = trim($this->input->post('select_mof'));
        $mof = $this->Mof->get_mof_by_id($mof_id);

        $replace_employee = $this->input->post('replace_employee') ? $this->input->post('replace_employee') : '-';
        list($employee_replaced_dni, $employee_replaced_name) = explode('-', $replace_employee);

        $data = [
            //'consultant_name' => $this->input->post('consultant_name'),
            //'client_company_name' => $this->input->post('client_company_name'),
            //'business_unit_name' => $this->input->post('business_unit_name'),
            //'cost_center' => $this->input->post('cost_center'),
            'job_title' => $mof->job_title,
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
            'location' => $this->input->post('location'),
            'working_hours' => $this->input->post('working_hours_manual'),
            'job_address' => $this->input->post('job_address'),
            'employee_replaced_dni' => !empty($employee_replaced_dni) ? $employee_replaced_dni : null,
            'employee_replaced_name' => !empty($employee_replaced_name) ? $employee_replaced_name : null,
        ];

        return $data;
    }

    private function build_data_internal_request()
    {
        $start_date_work = str_replace('/', '-', $this->input->post('start_date_work'));
        $mof_id = trim($this->input->post('select_mof'));
        $mof = $this->Mof->get_mof_by_id($mof_id);

        $data = [
            'user_management' => $this->input->post('user_management'),
            'salary_delivery_period' => $this->input->post('salary_delivery_period'),
            'applicant_headquarter' => $this->input->post('applicant_headquarter'),
            'start_date_work' => !empty($start_date_work) ? date('Y-m-d', strtotime($start_date_work)) : null,
            //'type_contracting' => $this->input->post('type_contracting'),
            'renovable' => $this->input->post('renovable'),
            'observations' => $this->input->post('observations'),
            'employee_change_file_path' => $this->input->post('reason_request') != 'new' ? $this->input->post('attached_file') : null,
            //'belonging_area_ID' => $this->input->post('belonging_area_id'),
            //'mof_ID' => $mof_id
        ];

        return $data;
    }

	private function validate_data_staff_request()
	{
		//Validate data request
		$this->form_validation->set_rules('type_requirement', 'Tipo de requerimiento', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('consultant_name', 'Consultora', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('client_company_name', 'Nombre de la empresa cliente', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('business_unit_name', 'Unidad de negocio', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');

		//Validate data area_mof
		//$this->form_validation->set_rules('belonging_area_id', 'Área perteneciente', 'trim|required');
		//$this->form_validation->set_rules('select_mof', 'MOF', 'trim|required');

		//Validate data job description
		$this->form_validation->set_rules('user_management', 'Gerencia usuaria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('applicant_headquarter', 'Jefatura del solicitante', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('vacancies', 'N° de vacantes', 'trim|required|is_natural|greater_than[0]');
		$this->form_validation->set_rules('name_immediate_boss', 'Nombre del jefe inmediato', 'trim|required|strip_all_tags');

		//Validate data hiring
		$reason_request = $this->input->post('reason_request');
		$minimum_salary = trim($this->input->post('minimum_salary'));

		//$this->form_validation->set_rules('type_contracting', 'Tipo de contratación', 'trim|required|in_list[temporary,fixed_term]');
		$this->form_validation->set_rules('charge_immediate_boss', 'Cargo del jefe inmediato', 'trim|strip_all_tags');      
		$this->form_validation->set_rules('contract_time_qty', 'Tiempo de contratación ', 'trim|required|is_natural|greater_than[0]');
		$this->form_validation->set_rules('contract_time_duration', 'Duración del tiempo de conratación', 'trim|required|in_list[año(s),mes(es),día(s)]');
		$this->form_validation->set_rules('renovable', 'Renovable', 'trim|required|in_list[yes,no]');
		$this->form_validation->set_rules('reason_request', 'Motivo de requerimiento', 'trim|required|in_list[replacement,new,vacations,license]');
		$this->form_validation->set_rules('minimum_salary', 'Rango de remuneración mínima', 'trim|numeric|greater_than[0]');      
		$this->form_validation->set_rules('maximum_salary', 'Rango de remuneración máxima', 'trim|' . ($minimum_salary != '' ? 'required|' : '' ) . 'numeric|greater_than_equal_to[' . $minimum_salary . ']');
		$this->form_validation->set_rules('monthly_gross_salary', 'Remuneración bruta mensual ', 'trim|required|numeric|greater_than[0]');
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

		//Validate data_authorities()
		$this->form_validation->set_rules('authorities[1][]', 'Autoridad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('authorities[2]', 'Autoridad', 'trim|required|strip_all_tags');       
		$this->form_validation->set_rules('authorities[3]', 'Autoridad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('authorities[4]', 'Autoridad', 'trim|required|strip_all_tags');

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
}
