<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		$this->load->model('Company_account_setting');
    }

    public function index()
    {
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Ajustes - ' . SITE_NAME;		

    	$this->load->view('employer/settings/setting_view', $data);
    }

    public function save()
    {
    	$all_input = $this->input->post();
		$employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

    	$status = $this->Company_account_setting->save($all_input, $employer->company_ID);

    	echo json_encode(array(
    		'success' => $status
    	));
    }
}
