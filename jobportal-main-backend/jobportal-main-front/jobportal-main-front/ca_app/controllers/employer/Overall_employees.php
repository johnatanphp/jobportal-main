<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Overall_employees extends CI_Controller 
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

    public function index()
    {
    	$this->search();
    }

    public function search()
    {
		$this->load->library(
        	'WS_overall/WS_overall_employee_lib', 
        	null, 
        	'WS_overall_employee_lib'
        );

        $data['title'] = 'Consulta empleados Overall - ' . SITE_NAME;
        $data['ads_row'] = $this->ads;

    	$query = trim($this->input->get('query') ?? '');

    	$result = [];

    	if ($query != '') {
    		$result = $this->WS_overall_employee_lib->search($query);
    	}

    	$data['query'] = $query;
    	$data['result_employees'] = $result;

    	$this->load->view('employer/overall_employees/search_employees', $data);
    }
                 
    public function search_detail_employee($doc_number = 0)
    {
		$seeker_experiences = $this->Job_seeker->get_overall_work_experiences([$doc_number]);
    	$data['employee_experiences'] = $seeker_experiences[$doc_number] ?? [];
		
		$blacklist = $this->Job_seeker->get_overall_blacklist([$doc_number]);
    	$data['employee_blacklist'] = $blacklist[$doc_number] ?? []; 

    	$this->load->view('employer/overall_employees/employee_detail_info', $data);
    }

	public function employee_overall_experiences($doc_number = 0)
    {
		$seeker_experiences = $this->Job_seeker->get_overall_work_experiences([$doc_number]);
    	$data['employee_experiences'] = $seeker_experiences[$doc_number] ?? [];

    	$this->load->view('employer/overall_employees/employee_detail_info', $data);
    }
}
