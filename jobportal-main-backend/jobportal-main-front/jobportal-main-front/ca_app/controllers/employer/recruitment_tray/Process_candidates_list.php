<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_list extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
        $this->load->model('Workflow_client');
        $this->load->model('Recruitment_tray_status');
    }
	
    public function index($client_code = null)
	{
	    check_permission_action('recruitment_tray', 'list_candidates');
					
        $data['title'] = 'Listado postulantes';
		$data['ads_row'] = $this->ads;

        $employer = $this->Employer->find($this->session->userdata('user_id'));

        $client = $this->Workflow_client->find([
            'code' => $client_code,
            'company_id' => $employer->company_ID
        ]);

        if (!$client) {
            show_404();
        }

        $data['client'] = $client;
        $data['tray_recruitment_status'] = $this->Recruitment_tray_status->all(['active' => 1]);
       
		$this->load->view('employer/recruitment_tray/recruitment_candidates/candidates_list', $data); 
	}

	public function search($client_code = null)
	{
        $employer = $this->Employer->find($this->session->userdata('user_id'));

        $client = $this->Workflow_client->find([
            'code' => $client_code,
            'company_id' => $employer->company_ID
        ]);

        if (!$client) {
            show_404();
        }

        echo json_encode($this->execute_search($client));
	}

    private function execute_search($client = null)
    {
        $params = $this->input->get();

        $user_id = $this->session->userdata('user_id');
        $employer = $this->Employer->find($this->session->userdata('user_id'));

        $filters =  [
            'status' => isset($params['status_ids']) && count((array)$params['status_ids']) > 0 ? $params['status_ids'] : [1, 2, 3, 4, 5],
            'origin_type' => isset($params['status_ids']) ? $params['origin_type'] : -1
        ];

		$total_rows = $this->count_all_candidates($client, $filters);
	
		$config = pagination_configuration(
            'process_candidates_list/search', 
            $total_rows, 
            $this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
            3, 
            5, 
            true, 
            true, 
            true
        );

		$this->pagination->initialize($config);
   		
        $page = (int)$this->input->get('page') < 1 ? 1 : (int)$this->input->get('page');
		$page_num = $page - 1;
		$per_page = $config['per_page'];
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $config["per_page"];

		$result = $this->search_candidates(
			$client,
			$per_page,
			$page,
            $filters
		);

        $data = [
            'employer' => $employer,
            'links' => $this->pagination->create_links(),
            'results' => $result,
            'search' => $params['search'] ?? ''
        ];

        if ((int)$this->input->get('page') == 0 && $total_rows == 0) {
            $search_view = $this->load->view('employer/recruitment_tray/recruitment_candidates/common/tray_candidates_empty', ['init' => 0], true);
        } else {
            $search_view = $this->load->view('employer/recruitment_tray/recruitment_candidates/common/tray_candidates_search', $data, true);
        }

		return [
            'data' => $search_view,
            'total_rows' => $total_rows,
            'total_rows_curent_page' => count($result)
        ];
    }

	private function search_candidates(
        $client,
        $per_page = 0, 
        $page = 0,
        $filters = []
    )
    {
        $employer_user = $this->Employer->find($this->session->userdata('user_id'));
        $employer_user_id = $employer_user->ID;

        $params = $this->input->get();
        $origin_type = $params['origin_type'];

        $this->db->select([
            'tc.id AS id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tc');
        $this->db->join('tbl_recruitment_process rc_process', 'tc.process_id=rc_process.id');
        $this->db->join('tbl_recruitment_candidates rc_candidates', 'rc_candidates.process_id=rc_process.id');
        $this->db->join('tbl_staff_requests tm_requests', 'tm_requests.ID=rc_process.request_id');
        $this->db->join('tbl_employer_permission_clients client_companies', 'client_companies.consultant_code=tm_requests.no_cia AND client_companies.client_code=tm_requests.cod_clie');
        $this->db->where('rc_process.tray_type_id', 2); //Procesos que vengan de la bandeja de la solicitudes TM
        $this->db->where('rc_candidates.discarded', 0);
        $this->db->where('rc_candidates.stage', 17);
        $this->db->where('tc.client_code', $client->code);
        $this->db->where('tc.company_id', $client->company_id);
        $this->db->where('client_companies.employer_id', $employer_user_id);
        $subquery_tray_rc_requests = $this->db->get_compiled_select();

        $this->db->select([
            'MAX(tc.id) AS id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tc');
        $this->db->join('tbl_recruitment_process rc_process', 'tc.process_id=rc_process.id');
        $this->db->where('rc_process.tray_type_id', 3); //Procesos que vengan de la bandeja sin solicitudes
        $this->db->where('tc.client_code', $client->code);
        $this->db->where('tc.company_id', $client->company_id);        
        $this->db->group_by('tc.seeker_id');
        $subquery_tray_candidates = $this->db->get_compiled_select();

        $sql_tray_candidates_all = "(" . $subquery_tray_rc_requests . ") UNION (" . $subquery_tray_candidates . ")";
        
        //Filtrar candidatos sin solicitudes
        if ($origin_type == 0) {
            $sql_tray_candidates_all = "(" . $subquery_tray_candidates . ")";
        }

        //Filtrar candidatos con solicitudes
        if ($origin_type == 1) {
            $sql_tray_candidates_all = "(" . $subquery_tray_rc_requests . ")";
        }

        $this->db->select([
            'tray_candidates.id AS id',
            'candidates.ID AS candidate_id',
            'candidates.first_name AS candidate_first_name',
            'candidates.last_name AS candidate_last_name',
            'candidates.document_number AS candidate_document_number',
            'dt.abbreviation AS candidate_document_type_abbreviation_name',
            'tray_candidates.status_id AS tray_status_id',
            'tray_candidates_status.name AS tray_status_name',
            'tray_candidates_status.bg_alert_color AS tray_status_bg_alert_color',
            'recruitment_tray_doc_percentage_progress(tray_candidates.seeker_id, recruitment_process.job_ID) AS doc_percentage_progress',
            'requests.ID AS request_id',
            'requests.job_title AS request_job_title',
            'tray_candidates.process_id'
        ]);
        
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join("(" . $sql_tray_candidates_all . ") AS tray_candidate_all", 'tray_candidates.id=tray_candidate_all.id');
        $this->db->join('tbl_recruitment_process recruitment_process', 'tray_candidates.process_id=recruitment_process.id');
        $this->db->join('tbl_recruitment_tray_status tray_candidates_status', 'tray_candidates.status_id=tray_candidates_status.id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=recruitment_process.request_id', 'left');
        $this->db->join('tbl_identity_document_types dt', 'dt.id=candidates.document_type', 'left');
        $this->db->join('tbl_recruitment_tray_sent_administrators sent_admin', 'sent_admin.sent_id=tray_candidates.sent_id', 'left');

        $this->db->where('tray_candidates.client_code', $client->code);
        $this->db->where('tray_candidates.company_id', $client->company_id);

        if ($employer_user->is_admin != 'yes') {
            $this->db->where('(tray_candidates.created_by=' . $employer_user_id . ' OR sent_admin.employer_id=' . $employer_user_id . ' OR recruitment_process.tray_type_id=2)');
        }
       
        if (isset($params['search']) && trim($params['search']) != '') {
            $this->db->where('candidates.document_number', trim($params['search']));
        }
        
        if (isset($filters['status'])) {
            $this->db->where_in('tray_candidates.status_id', $filters['status']);
        }

        $this->db->order_by('FIELD(tray_candidates.status_id, 
            "2", 
            "1",
            "4", 
            "5", 
            "3")'
        );

        $this->db->offset($page);
        $this->db->limit($per_page);
        
        $this->db->group_by(['tray_candidates.process_id', 'tray_candidates.seeker_id']);

        return $this->db->get()->result();
    }

    private function count_all_candidates(
        $client,
        $filters
    ) {
        $params = $this->input->get();
        $origin_type = $params['origin_type'];
        $employer_user = $this->Employer->find($this->session->userdata('user_id'));
        $employer_user_id = $employer_user->ID;
        
        $this->db->select([
            'tc.id AS id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tc');
        $this->db->join('tbl_recruitment_process rc_process', 'tc.process_id=rc_process.id');
        $this->db->join('tbl_recruitment_candidates rc_candidates', 'rc_candidates.process_id=rc_process.id');
        $this->db->join('tbl_staff_requests tm_requests', 'tm_requests.ID=rc_process.request_id');
        $this->db->join('tbl_employer_permission_clients client_companies', 'client_companies.consultant_code=tm_requests.no_cia AND client_companies.client_code=tm_requests.cod_clie');
        $this->db->where('rc_process.tray_type_id', 2); //Procesos que vengan de la bandeja de la solicitudes TM
        $this->db->where('rc_candidates.discarded', 0);
        $this->db->where('rc_candidates.stage', 17);
        $this->db->where('tc.client_code', $client->code);
        $this->db->where('tc.company_id', $client->company_id);
        $this->db->where('client_companies.employer_id', $employer_user_id);
        $subquery_tray_rc_requests = $this->db->get_compiled_select();

        $this->db->select([
            'MAX(tc.id) AS id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tc');
        $this->db->join('tbl_recruitment_process rc_process', 'tc.process_id=rc_process.id');
        $this->db->where('rc_process.tray_type_id', 3); //Procesos que vengan de la bandeja sin solicitudes
        $this->db->where('tc.client_code', $client->code);
        $this->db->where('tc.company_id', $client->company_id);        
        $this->db->group_by('tc.seeker_id');
        $subquery_tray_candidates = $this->db->get_compiled_select();

        $sql_tray_candidates_all = "(" . $subquery_tray_rc_requests . ") UNION (" . $subquery_tray_candidates . ")";
        
        //Filtrar candidatos sin solicitudes
        if ($origin_type == 0) {
            $sql_tray_candidates_all = "(" . $subquery_tray_candidates . ")";
        }

        //Filtrar candidatos con solicitudes
        if ($origin_type == 1) {
            $sql_tray_candidates_all = "(" . $subquery_tray_rc_requests . ")";
        }

        $this->db->select([
            'tray_candidates.seeker_id AS candidate_id',
        ]);
        
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join("(" . $sql_tray_candidates_all . ") AS tray_candidate_all", 'tray_candidates.id=tray_candidate_all.id');
        $this->db->join('tbl_recruitment_process recruitment_process', 'tray_candidates.process_id=recruitment_process.id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->join('tbl_recruitment_tray_sent_administrators sent_admin', 'sent_admin.sent_id=tray_candidates.sent_id', 'left');
        $this->db->where('tray_candidates.client_code', $client->code);
        $this->db->where('tray_candidates.company_id', $client->company_id);
        
        if ($employer_user->is_admin != 'yes') {
            $this->db->where('(tray_candidates.created_by=' . $employer_user_id . ' OR sent_admin.employer_id=' . $employer_user_id . ' OR recruitment_process.tray_type_id=2)');
        }
        
        if (isset($params['search']) && trim($params['search']) != '') {
            $this->db->where('candidates.document_number', trim($params['search']));
        }

        if (isset($filters['status'])) {
            $this->db->where_in('tray_candidates.status_id', $filters['status']);
        }

        $this->db->group_by(['tray_candidates.process_id', 'tray_candidates.seeker_id']);
    
        return $this->db->count_all_results();
    }
}
