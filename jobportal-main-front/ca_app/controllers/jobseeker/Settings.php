<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
/*
		if (!is_jobseeker_data_complete()) {
			redirect('jobseeker/my_account');
			exit;
		}

		*/
	}

	public function index()
	{

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Ajustes - ' . SITE_NAME;

		$this->load->view('jobseeker/settings/settings_view', $data);
	}

	public function save()
	{

		$all_input = $this->input->post();
		$user_id = $this->session->userdata('user_id');
	
    	$status = $this->Jobseeker_account_setting->save($all_input, $user_id);

    	echo json_encode(
    		array(
    			'success' => $status
    		)
    	);
	}
}
