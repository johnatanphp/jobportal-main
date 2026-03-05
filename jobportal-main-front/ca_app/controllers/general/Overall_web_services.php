<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Overall_web_services extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		if (!$this->session->userdata('user_id')) {
			show_404();
		}

        //Load Models
        $this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Workflow_cost_center');
    }

    public function search_employee_api()
    {
        $this->load->library(
        	'WS_overall/WS_overall_employee_lib', 
        	null, 
        	'WS_overall_employee_lib'
        );

    	$query = $this->input->get('q');

    	echo json_encode([
			'data_employees' => $this->WS_overall_employee_lib->search($query)
        ]);
    }

    public function get_consultants()
    {
        $user = $this->Employer->find($this->session->userdata('user_id'));

        $result = $this->Workflow_consultant->get_all($user->company_ID);

        echo json_encode([
            'MESSAGE' => 'OK',
            'CONSULTORA' => $result
        ]);
    }
    
    public function get_clients_company()
    {
        $no_cia = $this->input->post('no_cia');
        $uni_neg = $this->input->post('uni_neg');

        $user = $this->Employer->find($this->session->userdata('user_id'));

        $result = $this->Workflow_client->get_all($user->company_ID, $no_cia, $uni_neg);

        echo json_encode([
            'MESSAGE' => 'OK',
            'CLIENTE' => $result
        ]);
    }

    public function get_cost_centers()
    {
        $no_cia = $this->input->post('no_cia');
        $uni_neg = $this->input->post('uni_neg');
        $cod_clie = $this->input->post('cod_clie');
        
        $user = $this->Employer->find($this->session->userdata('user_id'));

        $result = $this->Workflow_cost_center->get_all($user->company_ID, $no_cia, $cod_clie, $uni_neg);

        echo json_encode([
            'MESSAGE' => 'OK',
            'CENTROCOSTO' => $result
        ]);
    }

    public function get_contract_models()
    {
        $this->load->library(
			'WS_overall/WS_overall_contract_model_lib', 
			null , 
			'WS_overall_contract_model_lib'
		);

		$results = $this->WS_overall_contract_model_lib->all([
			'no_cia' => $this->input->post('no_cia'),
			'client_code' => $this->input->post('client_code'),
            'charge_code' => $this->input->post('charge_code') 
		]);

        echo json_encode([
            'data' => $results
        ]);
    }
}
