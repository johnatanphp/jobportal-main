<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cv_Builder extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();

		//force record the data
		//validate_jobseeker_data();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME.': CV Gestionar';
		
		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($this->session->userdata('user_id'));
		
		//Personal Info
		$row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
		
		//Experience
		$result_experience = $this->Job_seeker->get_experience_by_jobseeker_id($this->session->userdata('user_id'));
		
		//Qualification
		$result_qualification = $this->Job_seeker->get_qualification_by_jobseeker_id($this->session->userdata('user_id'));
		
		$photo = ($row->photo)?$row->photo:'no_pic.jpg';
		$data['row']= $row;
		$data['result_experience']= $result_experience;
		$data['result_qualification']= $result_qualification;
		$data['result_cities'] 			= $this->City->get_all_cities();
		$data['result_countries'] 		= $this->Country->get_all_countries();
		$data['result_degrees'] 		= $this->Qualification->get_all_records();
		$data['photo'] = $photo;
		$this->load->view('jobseeker/cv_builder_view',$data);
	}
	
}
