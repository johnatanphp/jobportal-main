<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Create_model_3 extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        //Load models
        $this->load->model('Ubigeo');
        $this->load->model('Job_profile');
        $this->load->model('Business_unit');
        $this->load->model('Staff_request_type_reason');
        $this->load->model('Job_layout');
        
        //Load libraries
        $this->load->library(
            'Notification/Email/notification_email_staff_request_lib'
        );

        $this->ads = $this->Ad->get_ads();
    }

    public function index()
    {
        check_permission_action('staff_requests', 'create_external');
        
        $this->load->model('Cost_center_manager');
        $obj_recruiter = $this->Employer->find($this->session->userdata('user_id'));

        $user = $this->Employer->find($this->session->userdata('user_id'));
        $company = $this->Company->find($user->company_ID);

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Crear solicitud Modelo 3 - ' . SITE_NAME;  
        $data['recruiter'] = $obj_recruiter;
        $data['job_industries'] = $this->Industry->get_industries_actives();
        $data['qualifications'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => '1', 'country_id' => $company->country_id]);
        $data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => $company->country_id]);
        $data['additional_benefits'] = $this->Laboral_benefit->all(['active' => '1', 'company_id' => $company->ID]);
        $data['work_experiences'] = $this->Work_experience->all(['active' => '1', 'country_id' => $company->country_id]);
        $data['business_units'] = $this->Business_unit->all(['active' => '1', 'company_id' => $user->company_ID, 'business_unit_code' => 'MK']);
        $data['company'] = $company;
        $data['departments'] = $this->Ubigeo->get_all_departments($company->country_id);
        $data['type_reasons'] = $this->Staff_request_type_reason->all(['active' => 1]);
        $data['managers'] = $this->Cost_center_manager->get_all_managers();

        if (!$this->is_valid_data_request() ||
            !$this->is_valid_data_job_descriptions() || 
            !$this->is_valid_data_hiring() ||
            !$this->is_valid_salary_structure()) {

            $this->load->view('employer/staff_request/model_3/create', $data);
            return;
        }
        
        $this->load->library(
            'App/Staff_requests/App_staff_requests_create_model_3', 
            null, 
            'App_staff_requests_create_model_3'
        );

        $data = $this->input->post();
        $data['user_id'] = $this->session->userdata('user_id');

        $req_by_department = $this->input->post('req_by_department') ? true : false;
        $departments = (array)$this->input->post('department');

        if ($req_by_department) {

            $request_ids = [];

            foreach ($departments as $dep_row) {
                $data['department'] = $dep_row;
                
                $request_id = $this->App_staff_requests_create_model_3->create($data);
                
                if ($request_id) {
                    $request_ids[] = $request_id;
                }
            }
    
            if (count($request_ids) > 0) {
                $this->session->set_flashdata(
                    'msg', 
                    '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> La solicitud ha sido creada con éxito.</div>'
                );
                redirect('employer/staff_request/my_staff_requests');
                return;
            }
        }

        if (!$req_by_department) {
            $request_id = $this->App_staff_requests_create_model_3->create($data);

            if ($request_id) {
                $this->session->set_flashdata(
                    'msg', 
                    '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> La solicitud ha sido creada con éxito.</div>'
                );
                redirect('employer/staff_request/my_staff_requests');
                return;
            }
        }
        
        $this->session->set_flashdata(
            'msg', 
            '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error</strong> La solicitud no se pudo crear, por favor vuelve a intentar.</div>'
        );
        redirect('employer/staff_request/create_model_3');
    }

    private function is_valid_data_job_descriptions()
    {
        $req_by_department = $this->input->post('req_by_department') ? true : false;
        
        $this->form_validation->set_rules('type_requirement', 'Tipo de requerimiento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('campaign', 'Campaña', 'trim|max_length[60]|strip_all_tags');
        $this->form_validation->set_rules('service', 'Servicio', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('delivery_date', 'Fecha de entrega', 'callback_validate_delivery_date');
        $this->form_validation->set_rules('division', 'División', 'trim|strip_all_tags');
        $this->form_validation->set_rules('channel', 'Canal', 'trim|required|strip_all_tags');
        
        if ($req_by_department) {
            $this->form_validation->set_rules('vacancies', 'N° de vacantes', 'trim|required|is_natural|greater_than[0]');
            $this->form_validation->set_rules('department[]', 'Departamento', 'trim|required|strip_all_tags');
                
            if (in_array('Lima', (array)$this->input->post('department'))) {
                $this->form_validation->set_rules('zone[]', 'Zona', 'trim|required|strip_all_tags');
            }
        }

        if (!$req_by_department) {
            $this->form_validation->set_rules('dep_vacancies', 'Dep. Vacantes', 'callback_dep_vacancies');

            $dep_vacancies = (array)$this->input->post('dep_vacancies');

            foreach ($dep_vacancies as $row) {
                if ($row['department'] == 'Lima') {
                    $this->form_validation->set_rules('zone[]', 'Zona', 'trim|required|strip_all_tags');
                }
            }
        }

        $this->form_validation->set_rules('zone_comments', 'Zona', 'trim|strip_all_tags');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    private function is_valid_data_hiring()
    {
        $reason_request = $this->input->post('reason_request');

        $this->form_validation->set_rules('type_contract', 'Tipo de contrato', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list[male,female,both]');
        $this->form_validation->set_rules('health_card', 'Carnet de sanidad', 'trim|required|strip_all_tags');     
        $this->form_validation->set_rules('reason_request', 'Motivo de requerimiento', 'trim|required|in_list_db[tbl_staff_request_type_reasons.id]');
        $this->form_validation->set_rules('job_mode', 'Tipo de jornada laboral', 'trim|required|in_list[full_time,part_time,per_hours,weekends,telecommuting]');
        $this->form_validation->set_rules('working_hours_manual', 'Horario laboral', 'trim');
        $this->form_validation->set_rules('working_hours_manual', 'Horario laboral', 'trim|required|strip_all_tags');
        
        if ($reason_request == 'replacement' || 
            $reason_request == 'vacations' || 
            $reason_request == 'license') {
            $this->form_validation->set_rules('replace_employee', 'Trabajador a reemplazar', 'trim|required|strip_all_tags');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    public function is_valid_data_request()
    {
        $template = $this->input->post('request_template');

        $this->form_validation->set_rules('management', 'Gerencia', 'trim|strip_all_tags');
        $this->form_validation->set_rules('consultant_name', 'Consultora', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('client_company_name', 'Nombre de la empresa cliente', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('business_unit_name', 'Unidad de negocio', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('request_template', 'Plantilla', 'trim|required|strip_all_tags');
        
        //Plantilla Manual
        if ($template == '1') {
            $this->form_validation->set_rules('job_title', 'Puesto', 'trim|required|strip_all_tags');   
        }

        //Plantilla Perfil laboral
        if ($template == '2') {
            $this->form_validation->set_rules('job_profile', 'Perfil laboral', 'trim|required|strip_all_tags');
        }

        //Plantilla Layout de puesto
        if ($template == '3') {
            $this->form_validation->set_rules('job_layout', 'Layout de puesto', 'trim|required|strip_all_tags');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    public function is_valid_salary_structure()
    {
        $minimum_salary = trim($this->input->post('minimum_salary'));

        $this->form_validation->set_rules('minimum_salary', 'Rango de remuneración mínima', 'trim|numeric|greater_than[0]');        
        $this->form_validation->set_rules('maximum_salary', 'Rango de remuneración máxima', 'trim|' . ($minimum_salary != '' ? 'required' : '' ) . '|numeric|greater_than_equal_to[' . $minimum_salary . ']');
        $this->form_validation->set_rules('monthly_gross_salary', 'Remuneración bruta mensual ', 'trim|required|numeric|greater_than[0]');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        return $this->form_validation->run();
    }

    public function validate_step()
    {
        $step = $this->input->post('_step');
        $status = false;

        if ($step == 5) {
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

        if ($step == 4 && $this->is_valid_salary_structure()) {
            $status = true;
        }

        echo json_encode(array(
            'status_validation' => $status,
            'message_errors' => $this->form_validation->error_array()
        ));
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

    public function validate_delivery_date($date)
    {
        $type_requirement = $this->input->post('type_requirement');

        if ($type_requirement == 'REGULAR') {
            return true;
        }

        if ($type_requirement == 'ESPECIAL') {
            if (empty($date)) {
                $this->form_validation->set_message(
                    'validate_delivery_date', 'Por favor seleccione una fecha'
                );                
                return false;
            }

            $validation_date = strtotime(date('Y-m-d ') . "+ 7 day");
            $date = strtotime($date);

            if ($date < $validation_date) {
                $this->form_validation->set_message(
                    'validate_delivery_date', 'La fecha seleccionada debe ser mayor'
                );
                return false;
            }
        }
        
        return true;
    }

    // public function validate_zone($zone)
    // {
    //     $req_by_department = $this->input->post('req_by_department') ? true : false;
        
    //     if ($req_by_department) {
    //         $department = $this->input->post('department');

    //         if ($department == 'Lima' && trim($zone) == '') {
    //             $this->form_validation->set_message(
    //                 'validate_zone', 'Por favor seleccione una zona'
    //             );    
    //             return false;
    //         }    
    //     }

    //     if (!$req_by_department) {
    //         $dep_vacancies = (array)$this->input->post('dep_vacancies');

    //         foreach ($dep_vacancies as $row) {
    //             if ($row['department'] == 'Lima' && trim($zone) == '') {
    //                 $this->form_validation->set_message(
    //                     'validate_zone', 'Por favor seleccione una zona'
    //                 );    
    //                 return false;
    //             }
    //         }
    //     }
       
    //     return true;
    // }

    public function dep_vacancies($data)
    {       
        $dep_vacancies = (array)$this->input->post('dep_vacancies');

        if (count($dep_vacancies) == 0) {
            $this->form_validation->set_message(
                'dep_vacancies', 'Por favor ingrese al menos 1 Departamento y su vacante'
            );                
            return false;    
        }

        foreach ($dep_vacancies as $row) {
            
            if (trim($row['department']) == '') {
                $this->form_validation->set_message(
                    'dep_vacancies', 'Los departamentos no pueden estar vacios'
                );    
                return false;
            }

            if (trim($row['vacancies']) == '') {
                $this->form_validation->set_message(
                    'dep_vacancies', 'Las vacantes del departamento no pueden estar vacias'
                );    
                return false;
            }

            if (intval($row['vacancies']) <= 0) {
                $this->form_validation->set_message(
                    'dep_vacancies', 'Las vacantes deben ser mayor a 0'
                );    
                return false;
            }
        }

        return true;
    }
}
