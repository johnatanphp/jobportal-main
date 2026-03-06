<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_requests extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

    public function profile_export_pdf($request_id)
    {
    	if (!$this->session->userdata('is_employer')) {
    		redirect('login');
    	}

    	$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

    	if (!$staff_request) {
    		show_404();
    	}

		if ($staff_request->request_model_id == 1) {
			$data = get_data_staff_request_internal($request_id);
			$data['export_to_pdf'] = true;
			$html = $this->load->view('general/staff_request/common/personal_requirement', $data, true);
		}

		if ($staff_request->request_model_id == 2) {
			$data = get_data_staff_request($request_id);
			$data['export_to_pdf'] = true;
			$html = $this->load->view('general/staff_request/common/staff_request_external_labor_profile', $data, true);
		}

		if ($staff_request->request_model_id == 3) {
			$data = get_data_staff_request($request_id);
			$data['export_to_pdf'] = true;
			$html = $this->load->view('employer/staff_request/model_3/partials/staff_request_model_3', $data, true);
		}

		if ($staff_request->request_model_id == 4) {
			$data = get_data_staff_request($request_id);
			$data['export_to_pdf'] = true;
			$html = $this->load->view('employer/staff_request/model_4/common/staff_request_model_4', $data, true);
		}

		$this->load->library('Mpdf/mpdf_lib');
		$this->mpdf_lib->SetDisplayMode('fullpage');
		$this->mpdf_lib->SetTitle("Solicitud - Perfil laboral");
		$this->mpdf_lib->WriteHTML($html);
		$this->mpdf_lib->Output();
    }
}
