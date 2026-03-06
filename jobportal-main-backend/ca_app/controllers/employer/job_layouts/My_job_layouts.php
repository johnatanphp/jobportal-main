<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_job_layouts extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//Models
		$this->load->model('Job_layout');
    }

    public function index()
    {
    	$this->search();
    }

    public function search()
    {
    	$data['ads_row'] = $this->ads;
		$data['title'] = 'Listado de layouts creados - ' . SITE_NAME;

		$user = $this->Employer->find($this->session->userdata('user_id'));

		$filters = array(
			'query' => trim((string)$this->input->get('query', true)),
			'status' => trim((string)$this->input->get('status', true)),
			'recruiter_ID' => $this->session->userdata('user_id'),
			'company_id' => $user->company_ID
		);

		$total_rows = $this->count_all($filters);

		$config = pagination_configuration(
			pagination_url(), 
			$total_rows, 
			$this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
			3, 
			1, 
			true,
			true,
			true
		);

		$this->pagination->initialize($config);

        $page = ($this->input->get('page') ? (int)$this->input->get('page') : 0);
		$per_page = $config['per_page'];
		$page_num = $page - 1;
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $per_page;

		$results = $this->search_all($filters, $per_page, $page);
        
        $data['filters'] = $filters;
		$data['results'] = $results;
        $data['pagination'] = [
            'links' => $this->pagination->create_links(),
            'total_rows' => $total_rows,
            'current_page' => $page_num,
            'total_pages' => ceil((int)$total_rows / (int)$per_page),
            'per_page' => $per_page
        ];

		$this->load->view('employer/job_layouts/my_job_layouts', $data);
    }
	
	public function search_all($filter, $per_page, $page)
    {
        $sql_allowed_job_layouts = $this->Job_layout->get_sql_allowed_job_layouts_by_employer($this->session->userdata('user_id'));
    
        $this->db->select([
            'job_layout.id AS id',
            'job_layout.job_title AS job_title',
            'job_layout.code AS code',
            'job_layout.code_integration AS code_integration',
            'job_layout.active AS active'
        ]);
        $this->db->from('tbl_job_layouts job_layout');
        $this->db->join('(' . $sql_allowed_job_layouts . ') AS job_layouts_per', 'job_layouts_per.id=job_layout.id');
        
        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('job_layout.active', $filter['status']);
        }

        if (isset($filter['company_id']) && $filter['company_id'] != '') {
            $this->db->where('job_layout.company_id', $filter['company_id']);
        }

        if ($filter['query'] != '') {
            $this->db->like('job_layout.job_title', $filter['query']);
            $this->db->or_like('job_layout.code', $filter['query']);
            $this->db->or_like('job_layout.code_integration', $filter['query']);
            $this->db->or_like('job_layout.ID', $filter['query']);
        }

        $this->db->order_by('job_layout.active', 'DESC');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

	private function count_all($filter = [])
    {
        $sql_allowed_job_layouts = $this->Job_layout->get_sql_allowed_job_layouts_by_employer($this->session->userdata('user_id'));

        $this->db->select('job_layout.ID');
        $this->db->from('tbl_job_layouts job_layout');
        $this->db->join('(' . $sql_allowed_job_layouts . ') AS job_layouts_per', 'job_layouts_per.id=job_layout.id');

        if (isset($filter['company_id']) && $filter['company_id'] != '') {
            $this->db->where('job_layout.company_id', $filter['company_id']);
        }
        
        if (isset($filter['requested']) && $filter['requested'] != '') {
            $this->db->where('job_layout.requested', $filter['requested']);
        }

        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('job_layout.active', $filter['status']);
        }
               
        if ($filter['query'] != '') {
			$this->db->like('job_layout.job_title', $filter['query']);
            $this->db->or_like('job_layout.code', $filter['query']);
            $this->db->or_like('job_layout.code_integration', $filter['query']);
            $this->db->or_like('job_layout.ID', $filter['query']);
        }

        return $this->db->count_all_results(); 
    }
}
