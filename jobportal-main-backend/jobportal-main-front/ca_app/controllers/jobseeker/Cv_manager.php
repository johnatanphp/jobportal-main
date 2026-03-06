<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cv_Manager extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		
		$this->load->model('Institution_educational_type');
		$this->load->model('Institution_educational_class');
		$this->load->model('Institution_type');
		
		//Load libraries
		$this->load->library('Url_signer/Url_signer_lib');
		//force record the data
		//validate_jobseeker_data();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Dashboard - Bienvenido(a) '.$this->session->userdata('first_name');
		$row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
		
		//Applied Jobs by Seeker ID
		$result_applied_jobs = $this->Applied_jobs->get_applied_jobs_by_jobseeker_id($row->ID, 5, 0);
		
		//Experience
		$result_experience = $this->Job_seeker->get_experience_by_jobseeker_id($row->ID);
		
		//Qualification
		$result_qualification = $this->Job_seeker->get_qualification_by_jobseeker_id($row->ID);
		
		//Resumes
		$result_resume = $this->Resume->get_records_by_seeker_id($row->ID, 5, 0);
		
		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($row->ID);

		//Other studies
		$result_other_studies = $this->Jobseeker_other_studies->get_other_studies_by_seeker_id($row->ID);

		//Get countries
		$countries = $this->Country->get_all_countries();
		
		$photo = $row->photo;
		$data['row'] 					= $row;
		$data['result_experience'] 		= $result_experience;
		$data['result_qualification'] 	= $result_qualification;
		$data['result_applied_jobs']	= $result_applied_jobs;
		$data['result_cities'] 			= $this->City->get_all_cities();
		$data['result_countries'] 		= $this->Country->get_all_countries();
		$data['result_degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['result_degrees_other_studies'] = $this->Qualification->get_all_records_by_val('Otros Estudios');
		$data['result_industries']      = $this->Industry->get_all_industries();
		$data['result_resume'] 			= $result_resume;
		$data['result_other_studies'] 	= $result_other_studies;
		$data['row_additional'] 		= $row_additional;
		$data['photo'] 					= $photo;
		$data['countries']              = $countries;
		$data['result_institution_educ_types'] = $this->Institution_educational_type->all(['active' => 1]);
		$data['result_institution_educ_class'] = $this->Institution_educational_class->all(['active' => 1]);
		$data['result_institution_types'] = $this->Institution_type->all(['active' => 1]);

		$this->load->view('jobseeker/cv_manager', $data);
	}
	
	public function export_pdf()
	{
		$row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
		
		//Experience
		$result_experience = $this->Job_seeker->get_experience_by_jobseeker_id($row->ID);
		
		//Qualification
		$result_qualification = $this->Job_seeker->get_qualification_by_jobseeker_id($row->ID);
		
		//Resumes
		$result_resume = $this->Resume->get_records_by_seeker_id($row->ID, 5, 0);
		
		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($row->ID);

		//Other studies
		$result_other_studies = $this->Jobseeker_other_studies->get_other_studies_by_seeker_id($row->ID);

		//Get Skills jobseekers
		$result_skills = $this->Jobseeker_skills->get_records_by_seeker_id($row->ID);
		
		$photo = $row->photo;

		$data['row'] 					= $row;
		$data['result_experience'] 		= $result_experience;
		$data['result_qualification'] 	= $result_qualification;
		
		$data['result_degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['result_degrees_other_studies'] = $this->Qualification->get_all_records_by_val('Otros Estudios');
		$data['result_resume'] 			= $result_resume;
		$data['result_other_studies'] 	= $result_other_studies;
		$data['result_skills']   		= $result_skills;
		$data['row_additional'] 		= $row_additional;
		$data['job_questions']          = null;
		
		$data['photo'] 					= $photo;

	    $html = $this->load->view('jobseeker/common/cv_template_view', $data, true);
		
		$this->load->library('Mpdf/mpdf_lib');
		$this->mpdf_lib->SetDisplayMode('fullpage');
		$this->mpdf_lib->WriteHTML($html);
		$this->mpdf_lib->Output();
	}
}
