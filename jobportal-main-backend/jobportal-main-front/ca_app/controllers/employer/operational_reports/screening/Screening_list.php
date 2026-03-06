<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening_list extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

	public function index()
	{
		$data['ads_row'] = $this->ads;
        $data['title'] = 'Reporte listado de screening';
		$this->load->view('employer/operational_reports/screening/screening_list', $data);
	}

	public function export()
	{
		$params = $this->input->get();

        $this->form_validation->set_data($params);
        $this->form_validation->set_rules('start_date', 'Fecha de inicio', 'trim|required|valid_date');
        $this->form_validation->set_rules('end_date', 'Fecha fin', 'trim|required|valid_date|date_greater_than_equal_to[' . $params['start_date'] . ']');        

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors()
			]);
			return;
		}

		$start_date = $params['start_date'];
        $end_date = $params['end_date'];

        $start_date_time = new DateTime($start_date);
        $end_date_time = new DateTime($end_date);
        $diff = $start_date_time->diff($end_date_time);

        if ($diff->days > 62) {
            echo json_encode([
				'status' => false,
				'message' => 'El rango seleccionado no debe superar los 2 meses'
			]);
			return;
        }

		$this->load->library(
            'Exports/Operational_reports/Screening/Screening_list_export', 
            null, 
            'Screening_list_export'
        );

		$report_name = "Listado-screening-" . $start_date_time->format('Y-m-d') . "-al-" . $end_date_time->format('Y-m-d') . ".xlsx";

		$user = $this->Employer->find($this->session->userdata('user_id'));
		$report_class = $this->Screening_list_export;

        $report_class->build([
			'start_date' => $start_date_time->format('Y-m-d'),
			'end_date' => $end_date_time->format('Y-m-d'),
			'user_id' => $user->ID,
			'company_id' => $user->company_ID,
			'permission_business_unit' => true
		]);
		$report_class->download($report_name);	
	}
}