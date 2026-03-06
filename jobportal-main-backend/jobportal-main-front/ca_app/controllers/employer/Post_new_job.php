<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Post_New_Job extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
	
		$this->ads = $this->Ad->get_ads();
		//Load model
		$this->load->model('Mof');
		$this->load->model('Job_profile');
		$this->load->model('Job_layout');
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Nuevo empleo - ' . SITE_NAME;
		$data['msg'] = '';

		$request_id = trim((string)$this->input->get_post('r'));
		$request = $this->Staff_request->get_staff_request_by_id($request_id);

		if ($request && $request->sts_process != 'assigned') {
			$data['error_new_job'] = true;
			$data['request'] = $request;
			$this->load->view('employer/post_new_job_view', $data);
			return;
		}
	
		$row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));	

		$date_today = date('Y-m-d');

		$this->form_validation->set_rules('industry_id', 'Industria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('job_title', 'Titulo', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('vacancies', 'Vacantes', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('job_mode', 'Jornada laboral', 'trim|required|in_list[full_time,part_time,per_hours,weekends,telecommuting]');
		$this->form_validation->set_rules('last_date', 'Finaliza', 'trim|required|valid_date|date_greater_than_equal_to[' . $date_today . ']|strip_all_tags');
		$this->form_validation->set_rules('country', 'País', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('city', 'Ubicación / Ciudad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('qualification', 'Educación', 'trim');
		$this->form_validation->set_rules('editor1', 'Descripción del empleo', 'trim|required|secure');
		$this->form_validation->set_rules('experience', 'Experiencia requerida', 'trim|required');
		$this->form_validation->set_rules('s_val', 'Habilidades requeridas', 'trim|required|strip_all_tags', array('required' => 'Ingrese al menos 1 habilidad requerida'));

		$requires_payment = false;

		if ($this->input->post('min_pay') != '' && $this->input->post('max_pay') != '') {
			$this->form_validation->set_rules('currency_pay', 'Moneda', 'trim|required|strip_all_tags');			
			$this->form_validation->set_rules('min_pay', 'Pago mínimo', 'trim|required|numeric|greater_than[0]');
			$this->form_validation->set_rules('max_pay', 'Pago máximo', 'trim|required|numeric|greater_than_equal_to[' . $this->input->post('min_pay') . ']');
			$requires_payment = true;
		}
			
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		$company = $this->Company->find($row->company_ID);

		if ($this->form_validation->run() === FALSE) {
				
			$available_skills = '';
			$data['last_date_dummy'] = date('d/m/Y', strtotime("+4 months", strtotime(date("Y-m-d"))));
			$data['result_cities'] = $this->City->get_all_cities();
			$data['result_countries'] = $this->Country->get_all_countries();
			$data['result_industries'] = $this->Industry->get_all_industries();
			$data['result_salaries'] = $this->Salaries->get_all_records();
			$data['result_qualification'] = $this->Qualification->all(['val' => 'Estudios_t3', 'active' => 1, 'country_id' => $company->country_id]);
			$data['result_laboral_benefits'] = $this->Laboral_benefit->all(['company_id' => $row->company_ID]);		
			$data['result_ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);
			$data['result_experiences'] = $this->Work_experience->all(['active' => 1, 'country_id' => $company->country_id]);
			$data['country'] = $this->Country->find($company->country_id);

			$data['request'] = $request;

			foreach ($this->Skill->get_all_skills() as $skill_row) {
				$available_skills.='"'.$skill_row->skill_name.'", ';
			}
			
			$available_skills = '['.rtrim($available_skills,', ').']';
			$data['available_skills'] = $available_skills;	
			$data['row'] = $row;

			//Autoload data from staff request
			if ($request) {
				$data['request_data'] = $this->autoload_data_from_staff_request($request);
			}
			
			$this->load->view('employer/post_new_job_view',$data);
			return;
		}

		$required_skills = ltrim($this->input->post('s_val'),', ');
		$job_desc = strip_tags($this->input->post('editor1'),'<b><p><br><ul><li><strong><i><em>&nbsp;');
		$current_date_time = date("Y-m-d H:i:s");
		$last_date = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('last_date'))));
		$has_questions = trim((string)$this->input->post('has_questions')) != '' ? 'yes' : 'no';
		$show_salary_in_ad = trim((string)$this->input->post('show_salary_in_ad')) == 'yes' ? 'yes' : 'no';
		$laboral_benefits = implode(',', (array)$this->input->post('laboral_benefits'));

		$job_array = array(
			'industry_ID' => $this->input->post('industry_id'),
			'job_title' => humanize($this->input->post('job_title')),
			'vacancies' => $this->input->post('vacancies'),
			'job_mode' => $this->input->post('job_mode'),
			'payment_currency' => $requires_payment ? $this->input->post('currency_pay') : null,
			'minimum_payment' => $requires_payment ? $this->input->post('min_pay') : null,
			'maximum_payment' => $requires_payment ? $this->input->post('max_pay') : null,
			'experience' => $this->input->post('experience'),
			'last_date' => $last_date,
			'country' => $this->input->post('country'),
			'city' => $this->input->post('city'),
			'qualification' => $this->input->post('qualification'),
			'job_description' => $job_desc,
			'company_ID' => $row->company_ID,
			'employer_ID' => $row->ID,
			'required_skills' => $required_skills,
			'ip_address' => $this->input->ip_address(),
			'sts' => 'active',
			'dated' => $current_date_time,
			'has_questions' => $has_questions,
			'show_salary_in_ad' => $show_salary_in_ad,
			'laboral_benefits' => $laboral_benefits,
			'allow_people_disability' => $this->input->post('allow_people_disability') == 'yes' ? 'yes' : 'no',
			'request_ID' => $request ? $request->ID : null
		);

		$questions_data = $this->input->post('question');			

		$job_id = $this->Posted_job->add_posted_job($job_array, $questions_data, $has_questions);
		$job_slug = make_job_slug($row->company_slug, $this->input->post('job_title'), $job_id);

		$this->Posted_job->update_posted_job($job_id, array('job_slug' => $job_slug));
		
		if ($request) {
			$this->Staff_request->update_status_process($request->ID, 'published');
		}

		if ($this->input->post('open_rys') == 'true' && $job_id) {
			$data_open_process = [
				'job_ID' => $job_id,
				'sts' => 'active'
			];

			$this->db->insert('tbl_recruitment_process', $data_open_process);
		}

		$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> El nuevo empleo ha sido publicado exitosamente.</div>');
		redirect(base_url('employer/my_posted_jobs'),'');
	}

	public function add_skill($skills)
	{
		if (!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		$skills_array = explode(', ',$skills);
		
		foreach($skills_array as $skill) {
			if (!$this->Skill->get_skills_by_skill_name($skill)){
				$this->Skill->add(array('skill_name'=>$skill));
			}
		}
	}

	private function autoload_data_from_staff_request($request)
	{
		$request_data = null;
		$sr_model = $this->Staff_request;

		if ($request->request_type == 'external') {
			$request_data = $sr_model->get_external_staff_request_by_id($request->ID);
		}

		if ($request->request_type == 'internal') {
			$request_data = $sr_model->get_internal_staff_request_by_id($request->ID);		
		}

		$request_data->additional_benefits = $sr_model->get_additional_benefits_by_request_id($request->ID);
		$request_data->position_experience_time = '';
		$request_data->level_education = '';

		if ($request_data->mof_ID) {
			$mof = $this->Mof->get_mof_by_id($request_data->mof_ID);
			$request_data->position_experience_time = $mof->experience;
			$qualification = $this->Qualification->get_record_by_id($mof->study_grade_min);

			$request_data->level_education = $qualification ? $qualification['text'] : '';	
		}

		if ($request_data->job_profile_ID) {
			$profile = $this->Job_profile->find($request_data->job_profile_ID);
			$request_data->position_experience_time = $profile ? $profile->experience : '';
			$qualification = $this->Qualification->get_record_by_id($profile->study_grade_min);

			$request_data->level_education = $qualification ? $qualification['text'] : '';	
		}
	
		if ($request_data->job_layout_id) {
			$job_layout = $this->Job_layout->find($request_data->job_layout_id);
			$request_data->position_experience_time = $job_layout ? $job_layout->experience : '';
			$qualification = $this->Qualification->get_record_by_id($job_layout->study_grade_min);

			$request_data->level_education = $qualification ? $qualification['text'] : '';	
		}
	
		return $request_data;
	}
}
