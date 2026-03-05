<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Matching_Jobs extends CI_Controller {
	
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
		$data['title'] = SITE_NAME.': Sugerencias de empleo';
		
		//Job Seeker Skils
		$job_seeker_skills = $this->Job_seeker->get_grouped_skills_by_seeker_id($this->session->userdata('user_id'));
		$skills = explode(',', (string)$job_seeker_skills);

		$skill_qry='';
		if($skills){
			foreach($skills as $sk){
				$skill_qry.=" OR required_skills LIKE '%".trim($sk)."%'";
			}
		}
		else {
			$skill_qry.= " required_skills LIKE '%".trim($skills)."%'";
		}
		
		$skill_qry = ltrim($skill_qry,'OR ');
		
		//Jobs by skills
		$result_jobs = $this->Posted_job->get_matching_searched_jobs($skill_qry, 50, 0);
		$data['result']= $result_jobs;
		$this->load->view('jobseeker/matching_jobs_view',$data);
	}
	
	public function is_already_applied_for_job($user_id, $job_id){
		$is_already_applied = '';
		if($this->session->userdata('is_job_seeker')==TRUE){
			$is_already_applied = $this->Applied_jobs->count_applied_job_by_seeker_and_job_id($user_id, $job_id);
			$is_already_applied = ($is_already_applied>0)?'yes':'no';
		}	
		return $is_already_applied;
	}
	
}
