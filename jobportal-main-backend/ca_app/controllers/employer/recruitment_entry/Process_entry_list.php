<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_entry_list extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
        $this->load->model('Internal_area');
        show_404();
    }
	
	public function index()
	{
		$this->search();
	}

	public function search()
	{
		$data['title'] = 'Lista procesos';
		$data['ads_row'] = $this->ads;

		$rrhh_user_id = $user_id = $this->session->userdata('user_id');

        $rrhh_cost_centers = $this->db->get_where('tbl_rrhh_cost_centers', [
            'user_id' => $rrhh_user_id
        ])->result();

        $data_cost_centers[] = -1;

        foreach ($rrhh_cost_centers as $row) {
            $data_cost_centers[] = "'" . $row->cost_center . "'";
        }   

		$filters = [
			'query' => strip_tags((string)$this->input->get('query', true)),
			'area' => strip_tags((string)$this->input->get('area', true)),
            'contracted' => $this->input->get('contracted', true),
            'cost_centers' => $data_cost_centers,
            'job_id' => strip_tags((string)$this->input->get('job_id', true)),
		];
    
		$total_rows = $this->count_all_search_process(
			$rrhh_user_id,
			$filters
		);
	
		$config = pagination_configuration(
            pagination_url(), 
            $total_rows, 
            $this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
            3, 
            5, 
            true, 
            true, 
            true
        );

		$this->pagination->initialize($config);
   		
        $page = (int)$this->input->get('page');
		$page_num = $page - 1;
		$per_page = $config['per_page'];
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $config["per_page"];

		$result_candidates = $this->search_process(
			$rrhh_user_id,
			$filters,
			$per_page,
			$page
		);
        
        $data['job'] = $this->Posted_job->find($filters['job_id']);
		$data['links'] = $this->pagination->create_links();
		$data['internal_areas'] = $this->Internal_area->get_all_active_areas();
		$data['totals'] = $total_rows;
		$data['results'] = $result_candidates; 
		$data['filters'] = $filters;

		$this->load->view('employer/recruitment_entry/process_entry_list', $data); 
	}

	private function search_process(
        $rrhh_user_id,
        $filter, 
        $per_page = 0, 
        $page = 0
    )
    {
        $stage_contracting_process = 7; //Etapa de proceso de contratación

        $this->db->select([
            'recruitment_candidate.*',
            'rrhh_assignments.*',
            'job.*',
            'recruitment_candidate.job_ID',
            'COUNT(DISTINCT(recruitment_candidate.seeker_ID)) AS candidate_totals'
        ]);
        
        $this->db->from('tbl_recruitment_candidates recruitment_candidate');
        $this->db->join('tbl_job_seekers candidate', 'candidate.ID=recruitment_candidate.seeker_ID');
        $this->db->join('tbl_post_jobs job', 'job.ID=recruitment_candidate.job_ID');
        $this->db->join('tbl_staff_requests staff_requests', 'job.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_mof_belonging_areas mof_area', 'staff_requests.mof_ID=mof_area.mof_ID', 'left');
        $this->db->join('tbl_recruitment_rrhh_assignments rrhh_assignments', 'recruitment_candidate.job_ID=rrhh_assignments.job_ID', 'left');       
        $this->db->where('((staff_requests.cost_center IN (' . implode(",", $filter['cost_centers']). ')) OR (rrhh_assignments.rrhh_user_id=' . $rrhh_user_id .'))');
        $this->db->where('recruitment_candidate.stage', $stage_contracting_process);
        $this->db->where('recruitment_candidate.discarded', 0);

        if ($filter['contracted'] != '') {
            $this->db->where('recruitment_candidate.contracted', $filter['contracted']);
        }

        if ($filter['job_id'] != '') {
            $this->db->where('recruitment_candidate.job_id', $filter['job_id']);
        }

        $query = trim($filter['query']);

        if ($query != '') {
            $this->db->group_start();
            $query_is_digit = ctype_digit(strval($query));

            if ($query_is_digit) {
                $this->db->like('job.ID', $query);
                $this->db->or_like('staff_requests.ID', $query);
            } else {
                $this->db->like('job.job_title', $query);    
                $this->db->or_like('staff_requests.job_title', $query);
            }

            $this->db->group_end();
        }
        
        $this->db->order_by('recruitment_candidate.contracted', 'ASC');
        $this->db->order_by('recruitment_candidate.job_ID', 'DESC');

        $this->db->group_by(['job.ID']);
        
        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

    private function count_all_search_process(
        $rrhh_user_id,
        $filter
    ) {
        $stage_contracting_process = 7; //Etapa de proceso de contratación

        $this->db->select([
            'candidate.ID',
        ]);

        $this->db->from('tbl_recruitment_candidates recruitment_candidate');
        $this->db->join('tbl_job_seekers candidate', 'candidate.ID=recruitment_candidate.seeker_ID');
        $this->db->join('tbl_post_jobs job', 'job.ID=recruitment_candidate.job_ID');
        $this->db->join('tbl_staff_requests staff_requests', 'job.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_mof_belonging_areas mof_area', 'staff_requests.mof_ID=mof_area.mof_ID', 'left');
        $this->db->join('tbl_recruitment_rrhh_assignments rrhh_assignments', 'recruitment_candidate.job_ID=rrhh_assignments.job_ID', 'left');       
        $this->db->where('((staff_requests.cost_center IN (' . implode(",", $filter['cost_centers']) . ')) OR (rrhh_assignments.rrhh_user_id=' . $rrhh_user_id .'))');
        $this->db->where('recruitment_candidate.stage', $stage_contracting_process);
        $this->db->where('recruitment_candidate.discarded', 0);

        if ($filter['contracted'] != '') {
            $this->db->where('recruitment_candidate.contracted', $filter['contracted']);
        }

        if ($filter['job_id'] != '') {
            $this->db->where('recruitment_candidate.job_id', $filter['job_id']);
        }

        $query = trim($filter['query']);

        if ($query != '') {
            $this->db->group_start();
            $query_is_digit = ctype_digit(strval($query));

            if ($query_is_digit) {
                $this->db->like('job.ID', $query);
                $this->db->or_like('staff_requests.ID', $query);
            } else {
                $this->db->like('job.job_title', $query);    
                $this->db->or_like('staff_requests.job_title', $query);
            }

            $this->db->group_end();
        }

        $this->db->group_by(['job.ID']);
    
        return $this->db->count_all_results();
    }
}
