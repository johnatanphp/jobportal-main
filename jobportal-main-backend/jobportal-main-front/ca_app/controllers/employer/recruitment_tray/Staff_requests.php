<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_requests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
    }
    
    public function modal_detail($staff_request_id = 0)
    {
        $staff_request = $this->Staff_request->get_staff_request_by_id($staff_request_id);
    
       	if (!$staff_request) {
      		show_404();
      		exit;
       	}
      
       	if ($staff_request->request_type == 'external') {
			$data = get_data_staff_request($staff_request_id);
		}
      
		if ($staff_request->request_type == 'internal') {
			$data = get_data_staff_request_internal($staff_request_id);
		}
      
		$this->load->view('employer/recruitment_tray/staff_requests/common/modal_detail_staff_request_content', $data);     
    }
}
