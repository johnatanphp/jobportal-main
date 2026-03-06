<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_Posted_Job extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index($id = '')
	{
		$obj_posted_job = $this->Posted_job->get_posted_job_by_id($id);
	
		if (!$obj_posted_job){
			show_404();
			exit;
		}
		
		$row_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		if (!$row_employer) {
			show_404();
			exit;	
		}

		if ($obj_posted_job->company_ID != $row_employer->company_ID) {
			show_404();
			exit;			
		}

		$row = $this->Posted_job->get_posted_job_by_id_employer_id($id, $row_employer->ID);
		
		if ($row_employer->is_admin != 'yes' && !$row) {
			show_404();
		}

		$this->form_validation->set_rules('industry_id', 'Industria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('job_title', 'Título', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('vacancies', 'Vacantes', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('job_mode', 'Jornada laboral', 'trim|required|in_list[full_time,part_time,per_hours,weekends,telecommuting]');
		$this->form_validation->set_rules('country', 'País', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('city', 'Ubicación / Ciudad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('qualification', 'Educación', 'trim');
		$this->form_validation->set_rules('editor1', 'Descrpción del empleo', 'trim|required');
		$this->form_validation->set_rules('experience', 'Experiencia requerida', 'trim|required');
		$this->form_validation->set_rules('s_val', 'Habilidades requeridas', 'trim|required|strip_all_tags', array('required' => 'Ingrese al menos 1 habilidad requerida'));
		$this->form_validation->set_rules('finished_job', 'Empleo terminado', 'trim|required|in_list[yes,no]');

		$requires_payment = false;

		if ($this->input->post('min_pay') != '' && $this->input->post('max_pay') != '') {
			$this->form_validation->set_rules('currency_pay', 'Moneda', 'trim|required|strip_all_tags');			
			$this->form_validation->set_rules('min_pay', 'Pago mínimo', 'trim|required|numeric|greater_than[0]');
			$this->form_validation->set_rules('max_pay', 'Pago máximo', 'trim|required|numeric|greater_than_equal_to[' . $this->input->post('min_pay') . ']');
			$requires_payment = true;
		}

		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		$company = $this->Company->find($obj_posted_job->company_ID);

		if ($this->form_validation->run() === FALSE) {
			
			$data['title'] = 'Editar empleo - ' . SITE_NAME;
			$data['msg'] = '';
			$data['row'] = $obj_posted_job;
			$data['ads_row'] = $this->ads;
			$data['id'] = $id;
			$data['result_cities'] = $this->City->get_all_cities();
			$data['result_countries'] = $this->Country->get_all_countries();
			$data['result_industries'] = $this->Industry->get_all_industries();
			$data['result_salaries'] = $this->Salaries->get_all_records();
			$data['result_qualification'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['result_laboral_benefits'] = $this->Laboral_benefit->all(['company_id' => $obj_posted_job->company_ID]);		
			$data['result_ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);
			$data['result_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['country'] = $this->Country->find($company->country_id);

			$data['request'] = $this->Staff_request->get_staff_request_by_id($obj_posted_job->request_ID);

			$available_skills = "";

			foreach ($this->Skill->get_all_skills() as $skill_row) {
				$available_skills.='"'.$skill_row->skill_name.'", ';
			}

			$available_skills = '['.rtrim($available_skills,', ').']';
			$data['available_skills'] = $available_skills;
			
			$this->load->view('employer/edit_posted_job_view',$data);
			return;
		}

		$has_questions = trim((string)$this->input->post('has_questions')) != '' ? 'yes' : 'no';
		$show_salary_in_ad = trim((string)$this->input->post('show_salary_in_ad')) == 'yes' ? 'yes' : 'no';
		$required_skills = ltrim($this->input->post('s_val'),', ');
		$job_desc = strip_tags($this->input->post('editor1'),'<b><p><br><ul><li><strong><i><em>&nbsp;');
		$last_date = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('last_date'))));
		$job_slug = make_job_slug($row->company_slug, $this->input->post('job_title'), $id);
		$laboral_benefits = implode(',', (array)$this->input->post('laboral_benefits'));
		
		$job_array = array(
			'industry_id' => $this->input->post('industry_id'),
			'job_title' => $this->input->post('job_title'),
			'vacancies' => $this->input->post('vacancies'),
			'job_mode' => $this->input->post('job_mode'),
			'payment_currency' => $requires_payment ? $this->input->post('currency_pay') : null,
			'minimum_payment' => $requires_payment ? $this->input->post('min_pay') : null,
			'maximum_payment' => $requires_payment ? $this->input->post('max_pay') : null,
			'experience' => $this->input->post('experience'),
			'country' => $this->input->post('country'),
			'city' => $this->input->post('city'),
			'qualification' => $this->input->post('qualification'),
			'job_description' => $job_desc,
			'required_skills' => $required_skills,
			'job_slug' => $job_slug,
			'has_questions' => $has_questions,
			'show_salary_in_ad' => $show_salary_in_ad,
			'laboral_benefits' => $laboral_benefits,
			'allow_people_disability' => $this->input->post('allow_people_disability') == 'yes' ? 'yes' : 'no'
		);

		if ($this->input->post('update_last_date') == 'yes' && strtotime($last_date) > strtotime(date('Y-m-d'))) {
			$job_array['last_date'] = $last_date;
		}

		$questions_data = $this->input->post('question');

		$this->Posted_job->update_posted_job($id, $job_array, $questions_data, $has_questions);
		
		$this->add_skill($required_skills);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> El empleo se ha actualizado con éxito.</div>');
		redirect(base_url('employer/edit_posted_job/'.$id),'');		
	}
	
	public function delete_posted_job($id=''){
		
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		if($this->session->userdata('is_employer')!=TRUE){
			echo 'Illegal Request. Details are sent to administrator.';
			exit;	
		}
		
		if($id==''){
			echo 'Valid ID is missing.';
			exit;	
		}
		
		$this->Posted_job->delete_posted_job_by_id_emp_id($id, $this->session->userdata('user_id'));
		$this->Applied_jobs->delete_applied_job_by_posted_job_id($id);
		echo "done";
		
	}
	
	public function status($id = ''){
		
		$obj_posted_job = $this->Posted_job->get_posted_job_by_id($id);

		if (!$obj_posted_job) {
			echo 'error';
			exit;
		}

		$row_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		if (!$row_employer){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		if ($obj_posted_job->company_ID != $row_employer->company_ID) {
			echo 'Illegal Request. for company';
			exit;			
		}
		
		if ($this->session->userdata('is_employer') != TRUE) {
			echo 'Illegal Request. Details are sent to administrator.';
			exit;	
		}

		$obj_row = $this->Posted_job->get_posted_job_by_id_employer_id($id, $row_employer->ID);
		
		if ($row_employer->is_admin != 'yes' && !$obj_row) {
			echo 'It seems an illegal Request. Details are sent to administrator.';
			exit;
		}
		
		$current_status = $obj_posted_job->sts;
		
		if ($current_status == 'active') {
			$new_status = 'inactive';
		} else {
			$new_status = 'active';
		}
		
		$data = array ('sts' => $new_status);
		
		$this->Posted_job->update_posted_job($id, $data);
		echo $new_status;
	
		exit;
	}	
	
	public function add_skill($skills)
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		$skills_array = explode(', ',$skills);
		
		foreach($skills_array as $skill){
			if(!$this->Skill->get_skills_by_skill_name($skill)){
				$this->Skill->add(array('skill_name'=>$skill));
			}
		}
	}
}
