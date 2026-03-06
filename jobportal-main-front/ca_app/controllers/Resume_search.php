<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Resume_search extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
    	
		if ($this->session->userdata('is_admin_login') != TRUE && 
			$this->session->userdata('is_employer') != TRUE) {
			
			show_404();
		}
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		
		$param = trim($this->input->get('search'));
		$page = (int)$this->input->get('page') > 0 ? (int)$this->input->get('page') : 1;		
		$total_rows = $this->Resume->count_searched_resume_records($param);
	
		$config_pagination = pagination_configuration(base_url('resume_search?search=' . $param), $total_rows, 10, 2, 5, true, true, true);
		$this->pagination->initialize($config_pagination);

		$page_offset = ($page - 1) * $config_pagination['per_page'];
		$result_resumes = $this->Resume->get_searched_resume($param, $config_pagination['per_page'], $page_offset);
		$count_resumes = count($result_resumes);

		$data['total_rows'] = $total_rows;		
		$data['from_record'] = (($page - 1) * $count_resumes) + 1;
		$data['last_record'] = ($page_offset + $count_resumes);
		$data['result'] = $result_resumes;
		$data['param'] = html_escape($param);
		$data['links_pagination'] = $this->pagination->create_links();
		$data['title'] = $param.' Buscar currículum';
		$this->load->view('resume_search_view', $data);
	}
	
	public function left_side_data($param)
	{
		/*//Group By Title
		$title_group = $this->posted_jobs_model->get_searched_group_by_title($param);
		
		//Group By City
		$city_group = $this->posted_jobs_model->get_searched_group_by_city($param);
		
		//Group By Companies
		$company_group = $this->posted_jobs_model->get_searched_group_by_company($param);
		//Group By Salary Range
		$salary_range_group = $this->posted_jobs_model->get_searched_group_by_salary_range($param);
		
		$left_array =  array(
							'title_group' => $title_group,
							'city_group' => $city_group,
							'company_group' => $company_group,
							'salary_range_group' => $salary_range_group
		);
		
		return $left_array;*/
	}
}
