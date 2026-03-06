<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Resumes extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('storage_lib', null, 'Storage_lib');
	}

	public function index()
	{
	    show_404();
	}
	
	public function download($data_encrypt = null)
	{	
        $this->load->library('Url_signer/Url_signer_lib');
	
        $current_url = site_url($this->uri->uri_string());
        
        if ($_SERVER['QUERY_STRING']) {
            $current_url .= '?' . $_SERVER['QUERY_STRING'];
        }
        
        $url_is_valid = $this->url_signer_lib->validate($current_url);
        
        if (!$url_is_valid) {
            show_404();
        }
	
        $data_params = $this->custom_encryption->decrypt_data($data_encrypt, 1);
              
        $resume_id = $data_params['resume_id'] ?? 0;
        
		$resume = $this->Resume->get_records_by_id($resume_id);
	
		if (!$resume) {
			show_404();
		}

		if ($this->session->userdata('is_job_seeker') == true && 
			$this->session->userdata('user_id') != $resume->seeker_ID) {
			show_404();
		}
		
		if (!$this->Storage_lib->has($resume->file_name)) {
			show_404();
		}
	
		$file_url = file_url($resume->file_name);
	
		$seeker = $this->Job_seeker->get_job_seeker_by_id($resume->seeker_ID);
		$file_name = 'cv-' . make_slug($seeker->first_name . ' ' . $seeker->last_name) . '.' . file_ext($resume->file_name);

		$data = file_get_contents($file_url);

		force_download($file_name, $data);
	}
	
	public function add()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('job_title', 'job_title', 'trim|required');
		$this->form_validation->set_rules('company_name', 'company_name', 'trim|required');
		$this->form_validation->set_rules('exp_country', 'exp_country', 'trim|required');
		$this->form_validation->set_rules('exp_city', 'exp_city', 'trim|required');
		$this->form_validation->set_rules('start_date', 'start_date', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}
	}
	
	public function edit()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('job_title', 'job_title', 'trim|required');
		$this->form_validation->set_rules('company_name', 'company_name', 'trim|required');
		$this->form_validation->set_rules('exp_country', 'exp_country', 'trim|required');
		$this->form_validation->set_rules('exp_city', 'exp_city', 'trim|required');
		$this->form_validation->set_rules('start_date', 'start_date', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}
	}
	
	public function delete()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
		$this->form_validation->set_rules('fl', 'file', 'trim|required');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$resume = $this->Resume->get_records_by_id($this->input->post('id'));

		if (!$resume) {
			show_404();
			exit;	
		}
/*
		if ($this->session->userdata('is_user_login') !== true && 
			$this->session->userdata('is_admin_login') !== true) {
			show_404();
			exit;
		}
*/
		if ($this->session->userdata('is_job_seeker') != true || 
			$this->session->userdata('user_id') != $resume->seeker_ID) {
			show_404();
			exit;
		}

		$this->Resume->delete_by_id_seeker_id($this->input->post('id'), $this->session->userdata('user_id'));

		if ($this->Storage_lib->has($resume->file_name)) {
       		$this->Storage_lib->delete($resume->file_name);
       	}

       	echo "done";
	}
}
