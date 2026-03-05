<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_search extends CI_Controller
{	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index($path = '', $city = '')
	{  
		if ($_POST) {
			redirect($this->get_redirect_search());
			exit();
		}

		$data['ads_row'] = $this->ads;
		
		$this->load->library('translate_routes');

		$_get = $this->translate_routes->get_params_search_jobs($path, $city);

		$search = isset($_get['search']) ? trim($_get['search']) : '';  
		$search = str_replace('-', ' ', $search);
		$industry = isset($_get['industry']) ? trim($_get['industry']) : '';
		$city = isset($_get['city']) ? trim($_get['city']) : '';
		
        $page = (int)$this->input->get('page') > 0 ? (int)$this->input->get('page') : 1;

		$visitor = $this->session->userdata('visitor');

		$filters = [
			'country_id' => $visitor['selected_country_id'] ?? 56,
			'search' => $search, 
			'industry' => $industry, 
			'city' => $city
		];
	
		//Pagination starts
		$total_rows = $this->Posted_job->count_searched_job_records($filters);

		$config_pagination = pagination_configuration(base_url(uri_string()), $total_rows, 25, 3, 5, true, true, true);
		
		$this->pagination->initialize($config_pagination);
		
		$data["links"] = $this->pagination->create_links();
		//Pagination ends
		
		$page_offset = ($page - 1) * $config_pagination['per_page'];
		
		$result_jobs = $this->Posted_job->get_searched_jobs($filters, $config_pagination['per_page'], $page_offset);
		
		//Left Side Starts
		$left_side_array = $this->left_side_data($search, $city, $industry);
		
		//Left Side Ends
		$count_jobs = count($result_jobs);

		$searched_title = '';
		
		if ($search != '') {
			$searched_title = $search . ' - ';
		}

		if ($city != '') {
			$searched_title.='Empleos en ' . $city;
		} else {
			$searched_title.='Todos los empleos';
		}

		$country = $this->Country->find($visitor['selected_country_id']);

		$data['title'] = $searched_title;
		$data['total_rows'] = $total_rows;		
		$data['from_record'] = (($page - 1) * $count_jobs) + 1;
		$data['last_record'] = ($page_offset + $count_jobs);
		$data['result'] = $result_jobs;
		$data['left_side_industry'] = $left_side_array['industry_group'];
		$data['left_side_city'] = $left_side_array['city_group'];
		$data['left_side_company'] = $left_side_array['company_group'];
		$data['left_side_job_mode'] = $left_side_array['job_mode_group'];
		$data['cities_res'] = $this->City->get_all_active_cities();
		$data['search'] = html_escape($search);
		$data['city'] = html_escape($city);
		$data['industry'] = html_escape($industry);
		$data['country'] = $country;
		
		$this->load->view('job_search_view',$data);
	}

	public function get_redirect_search()
	{
		$search = $this->input->post('search') ?? '';
		$city = $this->input->post('city') ?? '';

		$search = strtolower(trim($search));
		$search = preg_replace('/[^a-zA-Z0-9 ]/s', '', $search);
		$search = preg_replace('/\s+/', '-', $search);

		$city = strtolower(trim($city));
		$city = preg_replace('/[^a-zA-Z0-9 ]/s', '', $city);
		$city = preg_replace('/\s+/', '-', $city);

		if ($search != '' && $city != '') {
			return $city . '/jobs-search-' . $search . '.html';
		}
	
		if ($search != '') {
			return 'jobs-search-' . $search . '.html';
		}

		if ($city != '') {
			return $city . '/jobs.html';
		}

		return 'jobs.html';
	}
	
	public function left_side_data($search, $city, $industry)
	{
		$visitor = $this->session->userdata('visitor');
		$country_id = $visitor['selected_country_id'] ?? '56';

		//Group By Job industry
		$job_industry_group = $industry == '' ? $this->Posted_job->get_searched_group_by_industry([
			'country_id' => $country_id,
			'search' => $search,
			'city' => $city
		]) : [];

		//Group By City
		//$city_group = $city == '' ? $this->Posted_job->get_searched_group_by_city($search, $industry) : [];
		$city_group = [];
		//Group By Companies
		$company_group = $this->Posted_job->get_searched_group_by_company([
			'country_id' => $country_id,
			'search' => $search,
			'city' => $city,
			'industry' => $industry
		]);
		
		//Group By Job mode
		$job_mode_group = [];

		$left_array =  [
			'industry_group' => $job_industry_group,
			'city_group' => $city_group,
			'company_group' => $company_group,
			'job_mode_group' => $job_mode_group
		];
		
		return $left_array;
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
