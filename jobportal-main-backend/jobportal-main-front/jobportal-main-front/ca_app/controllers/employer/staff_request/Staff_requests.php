<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_requests extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
        //Load models
		$this->load->model('Mof');
		$this->load->model('Job_profile');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_candidate');
		$this->load->model('Workflow_consultant');
		$this->load->model('Workflow_client');
		$this->load->model('Workflow_cost_center');
		$this->load->model('Cost_center_manager');
		$this->load->model('Job_layout');
		
		if (!has_permission_module('staff_requests')) {
		    show_404();
		}
		
		$this->ads = $this->Ad->get_ads();
    }
	
	public function select_type_request()
	{
		$obj_recruiter = $this->Employer->find(
			$this->session->userdata('user_id')
		);

		// if ($obj_recruiter->type == 'internal') {
		// 	redirect('employer/staff_request/create_internal_staff_request');
		// }

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Crear Solicitud - ' . SITE_NAME;
		$data['recruiter'] = $obj_recruiter;

		$this->load->view('employer/staff_request/select_type_request_view', $data);
		return;
	}

    public function show($request_id)
    {    	
    	$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

    	if (!$staff_request) {
    		show_404();
    		exit;
    	}
    
    	if ($staff_request->request_type == 'external') {
			$data = get_data_staff_request($request_id);
		}

		if ($staff_request->request_type == 'internal') {
			$data = get_data_staff_request_internal($request_id);
		}

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Ver solicitud - ' . $data['request']->job_title . ' - ' . SITE_NAME;
	
		$this->load->view('employer/staff_request/show_staff_request_view', $data);
    }

    public function export_excel_gantt_activities($request_id)
	{
		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

		if (!$staff_request) {
			show_404();
		}

		$this->load->library('gantt_request_excel');

		$this->gantt_request_excel->build_gantt($request_id);
		$this->gantt_request_excel->download();		
	}

	public function get_consultants()
	{
		$manager = $this->input->post('manager');
		$model_id = $this->input->post('model_id');
		
		$recruiter_info = $this->Employer->find($this->session->userdata('user_id'));

		if (!$recruiter_info) {
			show_404();
		}

		if ($model_id == '1') {
			$result = $result = $this->Workflow_consultant
				->get_all($recruiter_info->company_ID);
	
			echo json_encode([
				'MESSAGE' => 'OK',
				'CONSULTORA' => $result
			]);
			return;
		}

		if ($model_id == '2') {
			echo json_encode(
				$this->Employer->get_consultants(
					$recruiter_info->ID
				)
			);
			return;
		}

		if ($model_id == '3') {
			echo json_encode(
				$this->Cost_center_manager->get_consultants_by_manager_for_external(
					$recruiter_info->ID, $manager
				)
			);
			return;
		}
	}

	public function get_businnes_unit()
	{
		$recruiter_info = $this->Employer->find($this->session->userdata('user_id'));

		if (!$recruiter_info) {
			show_404();
		}

		$manager = $this->input->post('manager');
		$consultant = $this->input->post('consultant');

		echo json_encode(
			$this->Cost_center_manager
				->get_business_unit_by_manager_and_company_for_external(
					$recruiter_info->ID,
					$manager,
					$consultant
				)
			);
		return;
	}

	public function get_clients_company()
	{
		$recruiter_info = $this->Employer->find($this->session->userdata('user_id'));

		if (!$recruiter_info) {
			show_404();
		}

		$manager = $this->input->post('manager');
		$no_cia = $this->input->post('no_cia');
		$uni_neg = $this->input->post('uni_neg');
		$model_id = $this->input->post('model_id');

		$data = [
			'company_id' => $recruiter_info->company_ID,
			'recruiter_id' => $recruiter_info->ID,
			'manager' => $manager,
			'no_cia' => $no_cia,
			'uni_neg' => $uni_neg
		];

		if ($model_id == '1') {
			$result = $this->Workflow_client
				->get_all($recruiter_info->company_ID, $no_cia, $uni_neg);

			echo json_encode([
				'MESSAGE' => 'OK',
				'CLIENTE' => $result
			]);
			return;
		}

		if ($model_id == '2') {
			echo json_encode(
				$this->Employer->get_clients_company(
					$recruiter_info->ID,
					$no_cia, 
					$uni_neg
				)
			);
			return;
		}

		if ($model_id == '3') {
			echo json_encode(
				$this->Cost_center_manager
					->get_client_by_manager_consult_and_unit_for_external($data)
			);
			return;
		}
	}

	public function get_cost_centers()
	{
		$recruiter_info = $this->Employer->find($this->session->userdata('user_id'));

		if (!$recruiter_info) {
			show_404();
		}

		$no_cia = $this->input->post('no_cia');
		$uni_neg = $this->input->post('uni_neg');
		$cod_clie = $this->input->post('cod_clie');
		$manager = $this->input->post('manager');
		$model_id = $this->input->post('model_id');

		$data = [
			'manager' => $manager,
			'uni_neg' => $uni_neg,
			'no_cia' => $no_cia,
			'cod_clie' => $cod_clie,
			'recruiter_id' => $recruiter_info->ID,
			'company_id' => $recruiter_info->company_ID
		];

		if ($model_id == '1') {
			$result = $this->Workflow_cost_center
				->get_all($recruiter_info->company_ID, $no_cia, $cod_clie, $uni_neg);
			echo json_encode([
				'MESSAGE' => 'OK',
				'CENTROCOSTO' => $result
			]);
			return;
		}

		if ($model_id == '2') {
			echo json_encode(
				$this->Employer
				->get_cost_centers($recruiter_info->ID,	$no_cia, $uni_neg, $cod_clie)
			);
			return;
		}

		if ($model_id == '3') {
			echo json_encode(
				$this->Cost_center_manager
				->get_cost_center_by_manager_consult_unit_and_client_for_external($data)
			);
			return;
		}

	}

	public function get_areas()
	{
		$this->load->model('Workflow_area');

		$recruiter_info = $this->Employer->find($this->session->userdata('user_id'));

		if (!$recruiter_info) {
			show_404();
		}

		$no_cia = $this->input->post('no_cia');
		$areas = $this->Workflow_area->all([
			'active' => 1,
			'cia_code' => $no_cia,
			'company_id' => $recruiter_info->company_ID
		]);

        echo json_encode([
            'message' => 'OK',
            'status' => true,
			'areas' => $areas 
        ]);
	}

	public function get_data_mofs()
    {
        $area_id = trim($this->input->post('area_id'));
        $result_mofs = $this->Mof->get_mofs_by_belonging_area_id($area_id);

        echo json_encode(['mofs' => $result_mofs]);
    }

    public function upload_internal_staff_request_file()
	{
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid('employee-change', true)) . $file_ext;  

            $path = 'staff_requests/documents/' . $file_name;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'error' => 'Error al cargar el documento - No se pudo subir la imagen'
                ])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'location' => $path,
            'url_file' => $file_url,
            'original_file_name' => 'Documento'
        ]);
	}

    public function remove_attached_file()
    {
        $this->load->library('storage_lib');

        $file_path = $this->input->post('file_path');
     
        if ($file_path && 
            $this->storage_lib->has($file_path)) {
            $this->storage_lib->delete($file_path);
        }
     
        echo json_encode(['success' =>  true]);
    }

    public function get_authorities_DR_by_business_unit()
    {
		$user = $this->Employer->find($this->session->userdata('user_id'));

    	$this->load->model('Staff_request_authority');
        $sr_autority_model = $this->Staff_request_authority;
        $code_business_unit = trim($this->input->post('business_unit_name'));
        //get authorities 'DIRECTOR RESPONSABLES' assigned by business unit
        $result_authorities = $sr_autority_model->get_authorities_DR_by_business_unit_code(
			$user->company_ID,
            $code_business_unit
        );
        
        echo json_encode(['authorities' => $result_authorities]);
    }

	public function get_job_profiles()
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

    public function get_data_job_profile($job_profile_id = 0)
    {
    	$this->load->model('Job_profile');
    	
    	$job_profile = $this->Job_profile->find($job_profile_id);
		$data_job_profile['profile'] = $job_profile;
		$data_job_profile['skills'] = $this->Job_profile->get_skills_by_job_profile_id($job_profile_id);
		$data_job_profile['responsibilities'] = $this->Job_profile->get_responsibilities_by_job_profile_id($job_profile_id);
		$data_job_profile['benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $job_profile_id);

		echo json_encode([
			'job_profile' => $data_job_profile
		]);
    }

	public function get_eecc()
	{
		$param_input = $this->input->get();

		$data = [];

		if ($this->config->item('system_internal') == 'sap') {

			$employer = $this->Employer->find($this->session->userdata('user_id'));

			$company_id = $employer ? $employer->company_ID : null;

			$params = [
				'cia_code' => $param_input['cia_code'],
				'client_code' => $param_input['client_code'],
				'business_unit_code' => $param_input['business_unit_code'],
				'company_id' => $company_id
			];

			$this->load->library('WS_sap/WS_sap_eecc_lib', null, 'WS_sap_eecc_lib');
			$data = $this->WS_sap_eecc_lib->all($params);
		}

		if ($this->config->item('system_internal') == 'integrado') {
			$this->load->library(
				'WS_overall/WS_overall_eecc_lib', 
				null, 
				'WS_overall_eecc_lib'
			);
		
			$data = $this->WS_overall_eecc_lib->all([
				'cia_code' => $param_input['cia_code'],
				'client_code' => $param_input['client_code'],
				'cost_center' => $param_input['cost_center']
			]);
		}

		echo json_encode([
			'data' => $data			
		]);
	}

	public function edit($request_id)
	{
		$staff_request = $this->Staff_request->find($request_id);  	

		if (!$staff_request) {
			show_404();
		}

		if ($staff_request->request_model_id == 1) {
			redirect('employer/staff_request/profile_survey_internal/index/' . $request_id);
			return;
		}

		if ($staff_request->request_model_id == 2) {
			redirect('employer/staff_request/profile_survey_external/index/' . $request_id);
			return;
		}

		show_404();
	}

	public function clone_search_request()
	{
		$request_id = $this->input->post('request_id');

		$request = $this->Staff_request->find($request_id);

		if (!$request) {
			echo json_encode([
				'status' => false,
				'message' => 'Solicitud a clonar no encontrada'
			]);
			return;
		}

		$recruiter = $this->Employer->find(	$this->session->userdata('user_id'));

		if ($request->request_model_id == 1 && ($recruiter->type != 'internal' && $recruiter->type != 'internal-external')) {
			echo json_encode([
				'status' => false,
				'message' => 'Solicitud a clonar no autorizada'
			]);
			return;
		}

		if ($request->request_model_id == 2 && ($recruiter->type != 'external' && $recruiter->type != 'internal-external')) {
			echo json_encode([
				'status' => false,
				'message' => 'Solicitud a clonar no autorizada'
			]);
			return;
		}

		$request_data = $request;

		$request_data->list_working_hours = $this->Staff_request->get_working_hours_by_request_id($request_id);
		$request_data->additional_benefits = $this->Staff_request->get_additional_benefits_by_request_id($request_id);
		$request_data->computing_applicacions = $this->Staff_request->get_computing_applications_by_request_id($request_id);
		$request_data->languages = $this->Staff_request->get_languages_by_request_id($request_id);
		$request_data->job_functions = $this->Staff_request->get_job_functions_by_request_id($request_id);
		$request_data->additional_competences = $this->Staff_request->get_additional_competences_by_request_id($request_id);
		$request_data->fixed_competences = $this->Staff_request->get_fixed_competences_by_request_id($request_id);
		$request_data->request_resource = $this->Staff_request->get_resource_by_request_id($request_id);

		$model_id = $request->request_model_id;
		$consultants = [];
		$clients = [];
		$cost_centers = [];

		if ($model_id == '1') { 
			$consultants = $this->Workflow_consultant->get_all($request->company_ID);
			$clients = $this->Workflow_client->get_all($request->company_ID, $request->no_cia, $request->cod_business_unit);
			$cost_centers = $this->Workflow_cost_center->get_all($request->company_ID, $request->no_cia, $request->cod_clie, $request->cod_business_unit);
		}

		if ($model_id == '2') {
			$recruiter_info = $this->Employer->find($this->session->userdata('user_id'));
			$consultants = $this->Employer->get_consultants($recruiter_info->ID);
			$clients = $this->Employer->get_clients_company($recruiter_info->ID, $request->no_cia, $request->cod_business_unit);
			$cost_centers = $this->Employer->get_cost_centers($recruiter_info->ID, $request->no_cia, $request->cod_business_unit, $request->cod_clie);
		}

		$request_data->list_consultants = $consultants;
		$request_data->list_clients = $clients;
		$request_data->list_cost_centers = $cost_centers;

		echo json_encode([
			'status' => true,
			'data' => $request_data,
			'message' => 'OK'
		]);
	}

	public function get_job_layouts()
    {	
		$params = $this->input->post();
		$results = $this->Job_layout->get_all_by_permission_clients(
			$params['consultant_code'] ?? '', 
			$params['client_code'] ?? ''
		);
		echo json_encode([
			'status' => true,
			'data' => $results
		]);
    }
}
