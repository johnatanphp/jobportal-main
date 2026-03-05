<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Client_list extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		
		check_permission_action('recruitment_tray', 'list_candidates');
    }
	
	public function index()
	{			
        $data['title'] = 'Listado clientes';
		$data['ads_row'] = $this->ads;

        $filters = $this->filters();

        $data['content_search'] = $this->execute_search()['data'] ?? '';
		$data['filter_consultants'] = $filters['consultants'];
        $data['filter_clients'] = $filters['clients'];
        $data['filter_business_units'] = $filters['business_units'];
        $data['filter_cost_centers'] = $filters['cost_centers'];
        
		$this->load->view('employer/recruitment_tray/clients/client_list', $data); 
	}

	public function search()
	{
        echo json_encode($this->execute_search());
	}

    private function execute_search()
    {
        $user_id = $this->session->userdata('user_id');

		$total_rows = $this->count_all_search_clients(
			$user_id
		);
	
		$config = pagination_configuration(
            'client_list/search', 
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

		$result = $this->search_clents(
			$user_id,
			$per_page,
			$page
		);

		$data['links'] = $this->pagination->create_links();
        $data['totals'] = $total_rows;
		$data['results'] = $result; 

        $search_view = $this->load->view('employer/recruitment_tray/clients/common/client_search', $data, true);
        
		return [
            'data' => $search_view
        ];
    }

	private function search_clents(
        $user_id,
        $per_page = 0, 
        $page = 0
    )
    {
        $employer = $this->Employer->find($user_id);
        $params = $this->input->get();
        
        //$sql_employer_permissions_cost_centers = $this->Employer->get_sql_wf_employer_permissions_cost_centers($employer->ID);
         
        $this->db->select([
          'clients.code AS client_code',
          'clients.name AS client_name'
        ]);
        
        $this->db->from('tbl_workflow_clients clients');
        $this->db->join('tbl_employer_permission_clients AS permissions_clients', 'clients.code=permissions_clients.client_code');
        $this->db->join('tbl_workflow_cost_centers AS cost_centers', 'cost_centers.client_code=permissions_clients.client_code AND clients.company_id=cost_centers.company_id');
        $this->db->where('permissions_clients.employer_id', $user_id);
        $this->db->where('clients.company_id', $employer->company_ID);

        if (isset($params['consultant_codes']) && count($params['consultant_codes']) > 0) {
            $this->db->where_in('cost_centers.cia_code', $params['consultant_codes']);
        }

        if (isset($params['client_codes']) && count($params['client_codes']) > 0) {
            $this->db->where_in('cost_centers.client_code', $params['client_codes']);
        }

        if (isset($params['business_unit_codes']) && count($params['business_unit_codes']) > 0) {
            $this->db->where_in('cost_centers.business_unit_code', $params['business_unit_codes']);
        }

        if (isset($params['cost_center_codes']) && count($params['cost_center_codes']) > 0) {
            $this->db->where_in('cost_centers.code', $params['cost_center_codes']);
        }

        $this->db->offset($page);
        $this->db->limit($per_page);

        $this->db->group_by('clients.code');

        return $this->db->get()->result();
    }

    private function count_all_search_clients(
        $user_id
    ) {
        $employer = $this->Employer->find($user_id);
        $params = $this->input->get();
        
        $sql_employer_permissions_cost_centers = $this->Employer->get_sql_wf_employer_permissions_cost_centers($employer->ID);
        
        $this->db->select([
            'clients.code AS client_code'
        ]);
        
        $this->db->from('tbl_workflow_clients clients');
        $this->db->join('tbl_employer_permission_clients AS permissions_clients', 'clients.code=permissions_clients.client_code');
        $this->db->join('tbl_workflow_cost_centers AS cost_centers', 'cost_centers.client_code=permissions_clients.client_code AND clients.company_id=cost_centers.company_id');
        $this->db->where('permissions_clients.employer_id', $user_id);
        $this->db->where('clients.company_id', $employer->company_ID);
        
        if (isset($params['consultant_codes']) && count($params['consultant_codes']) > 0) {
            $this->db->where_in('cost_centers.cia_code', $params['consultant_codes']);
        }

        if (isset($params['client_codes']) && count($params['client_codes']) > 0) {
            $this->db->where_in('cost_centers.client_code', $params['client_codes']);
        }

        if (isset($params['business_unit_codes']) && count($params['business_unit_codes']) > 0) {
            $this->db->where_in('cost_centers.business_unit_code', $params['business_unit_codes']);
        }

        if (isset($params['cost_center_codes']) && count($params['cost_center_codes']) > 0) {
            $this->db->where_in('cost_centers.code', $params['cost_center_codes']);
        }

        $this->db->group_by('clients.code');

        return $this->db->count_all_results();
    }

    public function filters()
    {
        $user_id = $this->session->userdata('user_id');
        $employer = $this->Employer->find($user_id);
        
        $sql_employer_permissions_cost_centers = $this->Employer->get_sql_wf_employer_permissions_cost_centers($employer->ID);
        
        $this->db->select([
            'consultants.code AS consultant_code',
            'consultants.name AS consultant_name'
        ]);
        $this->db->from('tbl_workflow_consultants consultants');
        $this->db->join('tbl_workflow_cost_centers AS cost_centers', 'cost_centers.cia_code=consultants.code AND consultants.company_id=cost_centers.company_id');
        $this->db->join('tbl_employer_permission_clients AS permissions_clients', 'cost_centers.client_code=permissions_clients.client_code AND permissions_clients.consultant_code=consultants.code');
        $this->db->where('permissions_clients.employer_id', $user_id);
        $this->db->where('consultants.company_id', $employer->company_ID);

        $this->db->group_by('consultants.code');

        $consultants = $this->db->get()->result();

        $this->db->select([
            'clients.code AS client_code',
            'clients.name AS client_name'
        ]);
        
        $this->db->from('tbl_workflow_clients clients');
        $this->db->join('tbl_employer_permission_clients AS permissions_clients', 'permissions_clients.client_code=clients.code');
        $this->db->join('tbl_workflow_cost_centers AS cost_centers', 'cost_centers.cia_code=permissions_clients.consultant_code AND clients.company_id=cost_centers.company_id');
        $this->db->where('permissions_clients.employer_id', $user_id);
        $this->db->where('clients.company_id', $employer->company_ID);

        $this->db->group_by('clients.code');

        $clients = $this->db->get()->result();

        $this->db->select([
            'business_units.business_unit_code AS business_unit_code',
            'business_units.business_unit_name AS business_unit_name'
        ]);
        
        $this->db->from('tbl_business_units business_units');
        $this->db->join('tbl_workflow_cost_centers AS cost_centers', 'cost_centers.business_unit_code=business_units.business_unit_code AND business_units.company_id=cost_centers.company_id');
        $this->db->join('tbl_employer_permission_clients AS permissions_clients', 'permissions_clients.client_code=cost_centers.client_code AND permissions_clients.consultant_code=cost_centers.cia_code AND business_units.business_unit_code=cost_centers.business_unit_code');
        $this->db->where('permissions_clients.employer_id', $user_id);
        $this->db->where('business_units.company_id', $employer->company_ID);

        $this->db->group_by('business_units.business_unit_code');

        $business_units = $this->db->get()->result();

        $this->db->select([
            'cost_centers.code AS cost_center_code'
        ]);
        
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('tbl_employer_permission_clients AS permissions_clients', 'permissions_clients.client_code=cost_centers.client_code AND permissions_clients.consultant_code=cost_centers.cia_code');
        $this->db->where('permissions_clients.employer_id', $user_id);
        $this->db->where('cost_centers.company_id', $employer->company_ID);

        $this->db->group_by('cost_centers.code');

        $cost_centers = $this->db->get()->result();

        return compact(
            'consultants',
            'clients',
            'business_units',
            'cost_centers'
        );
    }
}
