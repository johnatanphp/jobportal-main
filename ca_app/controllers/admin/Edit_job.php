<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_Job extends CI_Controller {
	public function index($id='')
	{
		if($id==''){
			redirect(base_url().'admin/posted_jobs','');
			exit;
		}
		
		$available_skills ='';
		$data['id'] = $id;
		$data['title'] = SITE_NAME.' : Edit Posted Job';
		$data['msg']='';
		$data['result_cities'] = $this->City->get_all_cities();
		$data['result_countries'] = $this->Country->get_all_countries();
		$data['result_industries'] = $this->Industry->get_all_industries();
		$data['result_salaries'] = $this->Salaries->get_all_records();
		$data['result_qualification'] = $this->Qualification->get_all_records();
		foreach($this->Skill->get_all_skills() as $skill_row){
			$available_skills.='"'.$skill_row->skill_name.'", ';
		}
		$available_skills = '['.rtrim($available_skills,', ').']';
		$data['available_skills'] = $available_skills;
		$row = $this->Posted_job->get_posted_job_by_id($id);
		if(!$row){
			redirect(base_url().'admin/dashboard','');
			exit;	
		}
		$data['row'] = $row;
		
		$this->form_validation->set_rules('industry_id', 'Job category', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('job_title', 'Job title', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('vacancies', 'Vacancies', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('job_mode', 'Job mode', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('pay', 'Pay', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('last_date', 'Apply date', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('country', 'Country', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('city', 'City', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('qualification', 'Qualification', 'trim|required');
		$this->form_validation->set_rules('editor1', 'Job description', 'trim|required');
		$this->form_validation->set_rules('experience', 'Experience', 'trim|required|strip_all_tags');
	
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('admin/edit_job_view',$data);
			return;
		}
		
		$required_skills = ltrim($this->input->post('s_val'),', ');
		$job_desc = strip_tags($this->input->post('editor1'),'<b><p><br><ul><li><strong><i><em>&nbsp;');
		$last_date = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('last_date'))));
		$job_slug = make_job_slug($row->company_slug, $this->input->post('job_title'), $id);
		$job_array = array(
								'industry_id' => $this->input->post('industry_id'),
								'job_title' => $this->input->post('job_title'),
								'vacancies' => $this->input->post('vacancies'),
								'job_mode' => $this->input->post('job_mode'),
								'pay' => $this->input->post('pay'),
								'experience' => $this->input->post('experience'),
								'last_date' => $last_date,
								'country' => $this->input->post('country'),
								'city' => $this->input->post('city'),
								'qualification' => $this->input->post('qualification'),
								'job_description' => $job_desc,
								'required_skills' => $required_skills,
								'job_slug' => $job_slug
		);
		$this->Posted_job->update_posted_job($id, $job_array);
		$this->add_skill($required_skills);
		$this->session->set_flashdata('update_action', true);
		redirect(base_url('admin/edit_job/index/'.$id),'');		
	}
			
	public function add_skill($skills)
	{
		$skills_array = explode(', ',$skills);
		
		foreach($skills_array as $skill){
			if(!$this->Skill->get_skills_by_skill_name($skill)){
				$this->Skill->add(array('skill_name'=>$skill));
			}
		}
	}
}
