<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_job_profiles extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//Models
		$this->load->model('Job_profile');
		$this->load->model('Business_unit');
		$this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Workflow_cost_center');
    }

    public function index()
    {
    	$this->search();
    }

    public function search()
    {
    	$data['ads_row'] = $this->ads;
		$data['title'] = 'Listado de perfiles creados - ' . SITE_NAME;

		$user = $this->Employer->find($this->session->userdata('user_id'));

		$filters = array(
			'query' => trim((string)$this->input->get('query', true)),
			'status' => trim((string)$this->input->get('status', true)),
			'recruiter_ID' => $this->session->userdata('user_id'),
			'no_cia' => trim((string)$this->input->get('no_cia', true)),
			'cod_clie' => trim((string)$this->input->get('cod_clie', true)),
			'cod_business_unit' => trim((string)$this->input->get('cod_business_unit', true)),
			'cost_center' =>  trim((string)$this->input->get('cost_center', true)),
			'company_id' => $user->company_ID
		);

		if ($this->session->userdata('current_profile_id') == 2) {
			$filters['permission_cost_centers'] = $this->Employer->get_allowed_cost_centers($this->session->userdata('user_id'));
		}

		$total_rows = $this->Job_profile->count_all_for_user($filters);

		$config = pagination_configuration(
			pagination_url(), 
			$total_rows, 
			$this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
			3, 
			5, 
			true,
			true,
			true
		);

		$this->pagination->initialize($config);

        $page = ($this->input->get('page') ? (int)$this->input->get('page') : 0);
		$per_page = $config['per_page'];
		$page_num = $page - 1;
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $per_page;

		$results = $this->Job_profile->search_all_for_user($filters, $per_page, $page);
	
		$data['consultants'] = $this->Workflow_consultant->get_all($user->company_ID);
		
		$data['clients'] = $this->Workflow_client->get_all(
			$user->company_ID,  
			$filters['no_cia'], 
	 		$filters['cod_business_unit']
		);

		$data['cost_centers'] = $this->Workflow_cost_center->get_all(
			$user->company_ID, 
	 		$filters['no_cia'], 
			$filters['cod_clie'],
	 		$filters['cod_business_unit']
		);

		$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $user->company_ID]);
    
		$data['links'] = $this->pagination->create_links();	
		$data['job_profiles'] = $results;
		$data['total_rows'] = $total_rows;
		$data['filters'] = $filters;

		$this->load->view('employer/job_profile/my_job_profiles', $data);
    }	
}