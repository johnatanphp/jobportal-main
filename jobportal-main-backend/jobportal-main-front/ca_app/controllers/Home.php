<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Home extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		$country_code = $_GET['country'] ?? '';

		if (!empty((string)$country_code)) {
			$visitor = $this->session->userdata('visitor');
			$country_row = $this->Country->find(['iso_3166_1_alpha2' => $country_code, 'has_operation_overall' => 1]);

			if ($country_row) {
				$visitor['selected_country_id'] = $country_row->ID;
				$this->session->set_userdata('visitor', $visitor);
			}
		}
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		
		$data['title'] = 'Portal de empleo';

		$visitor = $this->session->userdata('visitor');
		$country_id = $visitor['selected_country_id'] ?? '56';

		//Pais seleccionado o. determinado por IP
		$country = $this->Country->find($country_id);

		//Latest jobs section
		$latest_jobs_result = $this->Posted_job->get_all_opened_jobs_by_country_id($country_id, 10, 0);

		$total_posted_jobs 	= $this->Posted_job->count_active_posted_jobs_by_country($country_id);
			
		//Feature jobs
		$featured_job_result = $this->Posted_job->get_active_featured_posted_job(6, 0);

		//Cities
		$data['cities_res'] = $this->City->get_all_active_cities();
		
		$data['country'] = $country;
		$data['total_posted_jobs'] = $total_posted_jobs;
		$data['latest_jobs_result'] = $latest_jobs_result;
		$data['featured_job_result'] = $featured_job_result;		
		$this->load->view('home_view',$data);
	}

	private function update_pass_users()
	{
		$this->db->from('tbl_job_seekers');
		$r = $this->db->get()->result();

		foreach ($r as $key => $row) {
			
			if (empty($row->password)) {
				continue;
			}

			$this->db->where('ID', $row->ID);
			$this->db->update('tbl_job_seekers', array(
				'password' => do_hashing($row->password)
			));
		}

		$this->db->from('tbl_employers');
		$r2 = $this->db->get()->result();

		foreach ($r2 as $key => $row) {
			
			if (empty($row->pass_code)) {
				continue;
			}
			
			$this->db->where('ID', $row->ID);
			$this->db->update('tbl_employers', array(
				'pass_code' => do_hashing($row->pass_code)
			));
		}
	}
}
