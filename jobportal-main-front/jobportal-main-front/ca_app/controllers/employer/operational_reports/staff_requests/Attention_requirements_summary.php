<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Attention_requirements_summary extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

	public function index()
	{
		$data['ads_row'] = $this->ads;
        $data['title'] = 'Reporte atención de requerimientos';
		$this->load->view('employer/operational_reports/staff_requests/attention_requirements_summary', $data);
	}

	public function export()
	{
		$params = $this->input->get();

		$user = $this->Employer->find($this->session->userdata('user_id'));

		$this->load->library(
            'Exports/Operational_reports/Staff_requests/Staff_request_attention_requirements_summary_export', 
            null, 
            'Staff_request_attention_requirements_summary_export'
        );

		$report_class = $this->Staff_request_attention_requirements_summary_export;

        $report_class->build([
			'year' => $params['year'],
			'request_model_id' => $params['request_model_id'],
			'user_id' => $user->ID,
			'company_id' => $user->company_ID
		]);
		$report_class->download('atencion-requerimientos-' . $params['year'] . '.xlsx');	
	}
}