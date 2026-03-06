<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class add_skills extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		if (!is_jobseeker_data_complete()) {
			redirect('jobseeker/my_account');
			exit;
		}
	}
	
	public function index()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$available_skills = '';
		$data['ads_row'] = $this->ads;
		$result = $this->Jobseeker_skills->get_records_by_seeker_id($this->session->userdata('user_id'));
		$data['title'] = $this->session->userdata('first_name').' - Mis habilidades';
		$data['result'] = $result;
		$data['count_skills'] = count($result);
		
		foreach($this->Skill->get_all_skills() as $skill_row){
			$available_skills.='"'.$skill_row->skill_name.'", ';
		}
		$available_skills = '['.rtrim($available_skills,', ').']';
		$data['available_skills'] = $available_skills;
		
		$this->load->view('jobseeker/add_skills_view',$data);
		return;
	}
	
	public function add()
	{
		if(!$this->session->userdata('user_id')){
			echo json_encode([
				'status' => false,
				'message' => 'Your session has been expired, please re-login first.'
			]);
			exit;	
		}
		
		$skill = trim(strtolower($this->input->post('skill')));
		$skill = strip_tags($skill);
		
		$row = $this->Jobseeker_skills->get_records_by_seeker_id_skill_name($this->session->userdata('user_id'),$skill);
		if($row){
			echo json_encode([
				'status' => false,
				'message' => 'La habilidad ya está registrada.'
			]);
			exit;
		}
		
		$data_array = array('seeker_ID' => $this->session->userdata('user_id'), 'skill_name' => $skill);
		$js_skill_id = $this->Jobseeker_skills->add($data_array);
		$skill_count = $this->Jobseeker_skills->count_jobseeker_skills_by_seeker_id($this->session->userdata('user_id'));

		echo json_encode([
			'status' => true,
			'message' => 'OK',
			'data' => [
				'id' => $js_skill_id,
				'skill_count' => $skill_count,				
			]
		]);
		exit;
	}
	
	public function remove()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->Jobseeker_skills->delete($this->input->post('skill'));
		echo $this->Jobseeker_skills->count_jobseeker_skills_by_seeker_id($this->session->userdata('user_id'));
		exit;
		
		
	}
}
