<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requirement_list extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

	public function index()
	{
	    $user_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
        $result_employers = $this->Employer->get_all_by_profile_id($user_employer->company_ID, 1);
        $result_recruiters = $this->Employer->get_all_by_profile_id($user_employer->company_ID, 2);
        
        $data = [
            'ads_row' => $this->ads,
            'title' => 'Listado de requerimientos',
            'result_employers' => $result_employers,
            'result_recruiters' => $result_recruiters
        ];
		$this->load->view('employer/operational_reports/staff_requests/requirement_list_summary', $data);
	}

	public function export()
	{
        $user_id = $this->session->userdata('user_id');
        
        // $business_unit_codes = $this->Employer_staff_request_manage_business_unit
        //                             ->get_business_units_by_user_id($user_id);

        $obj_employer = $this->Employer->find($user_id);

        //Obtener filtros
        $filters = [
            'request_year' => $this->input->get('year', true),
            'status_rq' => $this->input->get('status_rq', true),
            'status_rs' => $this->input->get('status_rs', true),
            'type' => $this->input->get('type', true),
            'recruiter' => $this->input->get('recruiter', true),
            'employer' => $this->input->get('employer', true),
            //'business_unit_codes' => $business_unit_codes,
            'company_id' => $obj_employer->company_ID
        ];
        
            $this->load->library('Exports/Operational_reports/Staff_requests/Staff_request_requeriment_list_export');

        $this->staff_request_requeriment_list_export->build($filters)->download('Listado-requerimientos-' . $filters['request_year'] . '.xlsx');	
	}
}
