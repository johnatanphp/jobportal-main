<?php
class Employer extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_employers');
        $this->db->where('ID', $id);

        return $this->db->get()->row();
    }

    public function add_employer($data)
    {
        $return = $this->db->insert('tbl_employers', $data);
        if ((bool) $return === TRUE) {
            return $this->db->insert_id();
        } else {
            return $return;
        }       
    	
    }	
	
	public function update_employer($id, $data)
    {
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_employers', $data);
		return $return;
	}
	
	public function update($id, $data){
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_employers', $data);
		return $return;
	}
	
	public function delete_employer($id){
		//$this->db->where('ID', $id);
		//$this->db->delete('tbl_employers');
	}
	
	public function authenticate_employer($user_name, $password) {
        $this->db->select('tbl_employers.*, tbl_companies.company_slug');
        $this->db->from('tbl_employers');
		$this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID', 'inner');
        $this->db->where('email', $user_name);
		$this->db->where('pass_code', $password);
		$this->db->limit(1);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function authenticate_employer_by_email($user_name) {
        $this->db->select('tbl_employers.*');
        $this->db->from('tbl_employers');
        $this->db->where('email', $user_name);
		$this->db->limit(1);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function authenticate_employer_by_password($ID, $password) {
        $this->db->select('tbl_employers.*');
        $this->db->from('tbl_employers');
        $this->db->where('ID', $ID);
		$this->db->where('pass_code', $password);
		$this->db->limit(1);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function is_email_already_exists($ID, $email) {
        $this->db->select('ID');
        $this->db->from('tbl_employers');
        $this->db->where('ID !=', $ID);
		$this->db->where('email', $email);
		$this->db->limit(1);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row('ID');
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

	public function get_all_employers($per_page, $page) {
        $this->db->select(array(
                'tbl_employers.*',
                'tbl_employers.ID AS user_id',
                'tbl_employers.top_employer',
                'tbl_companies.ID AS CID',
                'tbl_companies.company_name',
                'tbl_companies.company_logo',
                'tbl_companies.company_phone',
                'tbl_companies.company_location',
                'tbl_companies.company_slug',
            )
        );
        $this->db->from('tbl_employers');
		$this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID', 'left');
        $this->db->order_by("tbl_employers.ID", "DESC"); 

		$this->db->limit($per_page, $page);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }

    public function search_all_employers_by_company_id(
        $company_id,
        $filter, 
        $per_page, 
        $page
    )
    {
        $this->db->select([
            'tbl_employers.*',
            'tbl_employers.ID AS user_id'
        ]);

        $this->db->from('tbl_employers');
        $this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID');

        $this->db->where('tbl_employers.company_ID', $company_id);

        $email = trim((string)$filter['email']);

        if ($email != '') {
            $this->db->like('tbl_employers.email', $email);
        }

        $first_name = trim((string)$filter['first_name']);

        if ($first_name != '') {
            $this->db->like('tbl_employers.first_name', $first_name);
        }

        $this->db->order_by("tbl_employers.ID", "ASC"); 

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all_employers_by_company_id(
        $company_id,
        $filter = []
    )
    {
        $this->db->select('tbl_employers.ID');

        $this->db->from('tbl_employers');
        $this->db->where('tbl_employers.company_ID', $company_id);

        $email = trim((string)$filter['email']);

        if ($email != '') {
            $this->db->like('tbl_employers.email', $email);
        }

        $first_name = trim((string)$filter['first_name']);

        if ($first_name != '') {
            $this->db->like('tbl_employers.first_name', $first_name);
        }

        return $this->db->count_all_results();
    }
	
	public function record_count($table_name) {
		return $this->db->count_all($table_name);
    }
	
	public function get_employer_by_id($id) {
        $this->db->select('
            tbl_employers.*,
            tbl_employers.ID,
            tbl_employers.first_name, 
            tbl_employers.last_name, 
            tbl_employers.pass_code, 
            tbl_employers.mobile_phone, 
            tbl_employers.email, 
            tbl_employers.country, 
            tbl_employers.city, 
            tbl_employers.company_ID,
            tbl_companies.ID AS CID,
            tbl_companies.company_ruc, 
            tbl_companies.company_name,
            tbl_companies.company_email,
            tbl_companies.ownership_type,
            tbl_companies.company_ceo,
            tbl_companies.industry_ID,
            tbl_companies.company_description,
            tbl_companies.company_location,
            tbl_companies.no_of_offices,
            tbl_companies.company_website,
            tbl_companies.no_of_employees, 
            tbl_companies.established_in, 
            tbl_companies.company_logo, 
            tbl_companies.company_folder, 
            tbl_companies.company_type, 
            tbl_companies.company_fax, 
            tbl_companies.company_slug, 
            tbl_companies.company_phone, 
            tbl_companies.company_country, 
            tbl_companies.company_city, 
            tbl_job_industries.industry_name, 
            tbl_employers.is_admin
        ');
      
        $this->db->from('tbl_employers');
		$this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID', 'inner');
		$this->db->join('tbl_job_industries', 'tbl_companies.industry_ID = tbl_job_industries.ID', 'left');
		$this->db->where('tbl_employers.ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function get_employer_by_id_simple($id) {
        $this->db->select(
            array(
                'tbl_employers.*'
            )
        );
        $this->db->from('tbl_employers');
		$this->db->where('ID', $id);

        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function get_employer_by_company_id($cid) {
        $this->db->select('
            tbl_employers.*, 
            tbl_companies.ID AS CID,
            tbl_companies.company_name,
            tbl_companies.company_email,
            tbl_companies.company_ceo,
            tbl_companies.industry_ID,
            tbl_companies.ownership_type,
            tbl_companies.company_description,
            tbl_companies.company_location,
            tbl_companies.no_of_offices,
            tbl_companies.company_website,
            tbl_companies.no_of_employees,
            tbl_companies.established_in,
            tbl_companies.company_logo,
            tbl_companies.company_folder,
            tbl_companies.company_type,
            tbl_companies.company_fax,
            tbl_companies.company_phone
        ');
        $this->db->from('tbl_employers');
		$this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID', 'left');
		$this->db->where('tbl_employers.company_ID', $cid);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

    public function get_employers_by_company_id($cid)
    {    
        $this->db->select('
            tbl_employers.*, 
            tbl_companies.ID AS CID,
            tbl_companies.company_name,
            tbl_companies.company_email,
            tbl_companies.company_ceo,
            tbl_companies.industry_ID,
            tbl_companies.ownership_type,
            tbl_companies.company_description,
            tbl_companies.company_location,
            tbl_companies.no_of_offices,
            tbl_companies.company_website,
            tbl_companies.no_of_employees, 
            tbl_companies.established_in, 
            tbl_companies.company_logo, 
            tbl_companies.company_folder, 
            tbl_companies.company_type, 
            tbl_companies.company_fax, 
            tbl_companies.company_phone
        ');

        $this->db->from('tbl_employers');
        $this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID');
        $this->db->where('tbl_employers.company_ID', $cid);
        
        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
	
//====== Searching Employers =======	
	public function search_all_employers($per_page, $page, $search_parameters, $wild_card='') {
		
		$where = ($wild_card=='yes')?'where':'like';
       
        $this->db->select([
            'tbl_employers.*',
            'tbl_employers.ID AS user_id', 
            'tbl_employers.top_employer',
            'tbl_companies.ID AS CID',
            'tbl_companies.company_name',
            'tbl_companies.company_logo',
            'tbl_companies.company_phone',
            'tbl_companies.company_location',
            'tbl_companies.company_slug'    
        ]);

        $this->db->from('tbl_employers');
		$this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID', 'left');
		$this->db->$where($search_parameters);
		$this->db->order_by("tbl_employers.ID", "DESC"); 
		$this->db->limit($per_page, $page);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
		//echo $this->db->last_query(); exit;
        return $return;
    }
	
	public function search_record_count($table_name, $search_parameters) {
		//return $this->db->count_all($table_name);
		$this->db->like($search_parameters);
		$this->db->from($table_name);
		$this->db->join('tbl_companies', 'tbl_employers.company_ID = tbl_companies.ID', 'left');
		return $this->db->count_all_results();
		//exit;
    }
//====== Specifically front end methods =======	
	public function get_all_active_employers($per_page, $page) {
        $Q = $this->db->query("SELECT * FROM get_all_active_employers($page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }	
	
	public function get_all_active_top_employers($per_page, $page) {
        $Q = $this->db->query("SELECT * FROM get_all_active_top_employers($page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
    
	/* PA sin usar*/
	public function get_company_details_by_slug($slug) {
        $Q = $this->db->query("SELECT * FROM get_company_by_slug('$slug')");
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

    public function get_suggestions_active_employers_by_company_id($cid, $term, $limit = 20)
    { 
        $this->db->select(
            array(
                'tbl_employers.*'
            )
        );
 
        $this->db->from('tbl_employers');
        $this->db->where('tbl_employers.company_ID', $cid);
        $this->db->where('tbl_employers.sts', 'active');

        $this->db->group_start();        
        $this->db->like('tbl_employers.first_name', $term, 'both');
        $this->db->or_like('tbl_employers.email', $term, 'both');
        $this->db->group_end();

        $this->db->limit($limit);

        $employers = $this->db->get()->result();
        
        $data = array();

        foreach ($employers as $row_employer) {
            
            $data[] = array(
                'value' => $row_employer->ID, 
                'label' => $row_employer->first_name . ' - ' . $row_employer->email,
                'email' => $row_employer->email,
                'name' => $row_employer->first_name
            );
        }

        return $data;
    }

    public function get_admin_employer_by_company_id($company_id)
    {
        $this->db->select(
            array(
                'tbl_employers.*'
            )
        );

        $this->db->from('tbl_employers');
        $this->db->where('tbl_employers.company_ID', $company_id);
        $this->db->where('tbl_employers.is_admin', 'yes');

        return $this->db->get()->row();
    }

    public function get_active_employers_by_company_id($cid)
    {    
        $this->db->select(
            array(
                'tbl_employers.email', 
                'tbl_employers.first_name',
                'tbl_employers.last_name', 
                'tbl_employers.ID'
            )
        );

        $this->db->from('tbl_employers');
        $this->db->where('tbl_employers.company_ID', $cid);
        $this->db->where('tbl_employers.sts', 'active');
        
        return $this->db->get()->result();
    }

    public function get_internal_by_profile_id($company_id, $profile_id)
    {
		$this->db->select('user.*');
		$this->db->from('tbl_employers user');
		$this->db->join('tbl_employer_profiles profiles', 'user.ID=profiles.user_id');
        $this->db->join('tbl_companies companies', 'user.company_ID=companies.ID');
        $this->db->where('user.sts', 'active');
        
        $this->db->where('user.company_ID', $company_id);

        if (is_array($profile_id)) {
            $this->db->where_in('profiles.profile_id', $profile_id);
        } else {
            $this->db->where('profiles.profile_id', $profile_id);
        }
        
        $this->db->where('companies.system_internal', 1);
        
        return $this->db->get()->result();
    }

    public function get_all_by_profile_id($company_id, $profile_id)
    {
		$this->db->select('user.*');
		$this->db->from('tbl_employers user');
		$this->db->join('tbl_employer_profiles profiles', 'user.ID=profiles.user_id');
        $this->db->join('tbl_companies companies', 'user.company_ID=companies.ID');
        $this->db->where('user.sts', 'active');
        
        $this->db->where('user.company_ID', $company_id);

        if (is_array($profile_id)) {
            $this->db->where_in('profiles.profile_id', $profile_id);
        } else {
            $this->db->where('profiles.profile_id', $profile_id);
        }
        
        //$this->db->where('companies.system_internal', 1);
        
        return $this->db->get()->result();
    }

    public function get_client_companies($recruiter_id)
    {
        $this->db->select([
            'client_companies.*',
            'clients.name AS client_company_name',
            'consultants.name AS consultant_name',
            'business_units.business_unit_name AS business_unit_name'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies client_companies');
        $this->db->join('tbl_employers employers', 'employers.ID=client_companies.recruiter_ID');
        $this->db->join('tbl_workflow_clients clients', 'clients.code=client_companies.cod_clie AND clients.company_id=employers.company_ID', 'left');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=client_companies.no_cia AND consultants.company_id=employers.company_ID', 'left');
        $this->db->join('tbl_business_units business_units', 'business_units.business_unit_code=client_companies.cod_business_unit AND business_units.company_id=employers.company_ID', 'left');
        
        $this->db->where('client_companies.recruiter_ID', $recruiter_id);
    
        return $this->db->get()->result();
    }

    public function get_permission_consultants($filters = [])
    {
        $employer_id = $filters['permission_employer_id'] ?? 0;
        $consultant_code = $filters['code'] ?? '';
        
        $sql_workflow_employer_permissions = $this->get_sql_wf_employer_permissions_cost_centers($employer_id);
        
        $this->db->select([ 
            'consultants.code AS consultant_code',
            'consultants.name AS consultant_name',
        ]);
        
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('(' . $sql_workflow_employer_permissions . ') AS tmp_workflow_employer_permissions', 'tmp_workflow_employer_permissions.consultant_code=cost_centers.cia_code');
        $this->db->join('tbl_employers employers', 'employers.ID=tmp_workflow_employer_permissions.employer_id');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=tmp_workflow_employer_permissions.consultant_code AND consultants.company_id=employers.company_ID');
        $this->db->where('employers.ID', $employer_id);
        
        if ($consultant_code) {
            $this->db->where('consultants.code', $consultant_code);
        }
        
        $this->db->group_by('consultants.code');

        return $this->db->get()->result();
    }
    
    public function get_consultants($employer_id)
    {
        $results = $this->get_permission_consultants([
            'permission_employer_id' => $employer_id
        ]);

        $rows_data = [];

        foreach ($results as $key => $row) {

            $rows_data[] = [
                'CONSULTORA' => $row->consultant_name,
                'NO_CIA' => $row->consultant_code
            ];
        }

        return [
            'MESSAGE' => 'OK',
            'CONSULTORA' => $rows_data
        ];
    }
    
    public function get_permission_business_units(
        $employer_id
    ) {
        $sql_workflow_employer_permissions = $this->get_sql_wf_employer_permissions_cost_centers($employer_id);

        $this->db->select([
            'business_units.business_unit_code',
            'business_units.business_unit_name'
        ]);

        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('(' . $sql_workflow_employer_permissions . ') AS tmp_workflow_employer_permissions', 'tmp_workflow_employer_permissions.business_unit_code=cost_centers.business_unit_code');
        $this->db->join('tbl_employers employers', 'employers.ID=tmp_workflow_employer_permissions.employer_id');
        $this->db->join('tbl_business_units business_units', 'business_units.business_unit_code=tmp_workflow_employer_permissions.business_unit_code AND business_units.company_id=employers.company_ID');
        $this->db->where('employers.ID', $employer_id);
        $this->db->where('business_units.active', 1);
       
        $this->db->group_by('business_units.business_unit_code');

        return $this->db->get()->result();
    }
    
    public function get_permission_clients($filters = [])
    {    
        $employer_id = $filters['permission_employer_id'] ?? null; 
        $consultant_code = $filters['consultant_code'] ?? null;
        $business_unit_code = $filters['business_unit_code'] ?? null;
        $client_code = $filters['client_code'] ?? null;
        
        $sql_workflow_employer_permissions = $this->get_sql_wf_employer_permissions_cost_centers($employer_id);
         
        $this->db->select([
            'clients.code AS client_code',
            'clients.name AS client_name'
        ]);        
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('(' . $sql_workflow_employer_permissions . ') AS tmp_workflow_employer_permissions', 'tmp_workflow_employer_permissions.client_code=cost_centers.client_code');
        $this->db->join('tbl_employers employers', 'employers.ID=tmp_workflow_employer_permissions.employer_id');
        $this->db->join('tbl_workflow_clients clients', 'clients.code=tmp_workflow_employer_permissions.client_code AND clients.company_id=employers.company_ID');
        
        if ($consultant_code) {    
            $this->db->where('tmp_workflow_employer_permissions.consultant_code', $consultant_code);
        }
        
        if ($client_code) {
            $this->db->where('tmp_workflow_employer_permissions.client_code', $client_code);
        }
        
        if ($business_unit_code) {
            $this->db->where('tmp_workflow_employer_permissions.business_unit_code', $business_unit_code);
        }
        
        $this->db->where('employers.ID', $employer_id);
        $this->db->where('clients.active', 1);
       
        $this->db->group_by('clients.code');
        
        return $this->db->get()->result();
    }
    
    public function get_clients_company(
        $employer_id, 
        $consultant_code, 
        $business_unit_code
    ) {
        
        $results = $this->get_permission_clients([
            'permission_employer_id' => $employer_id,
            'consultant_code' => $consultant_code,
            'business_unit_code' => $business_unit_code
        ]);

        $rows_data = [];

        foreach ($results as $key => $row) {

            $rows_data[] = [
                'CLIENTE' => $row->client_name,
                'COD_CLIE' => $row->client_code
            ];
        }

        return [
            'MESSAGE' => 'OK',
            'CLIENTE' => $rows_data
        ];
    }

    public function get_permission_cost_centers(
        $filters = []
    ) {
        
        $employer_id = $filters['permission_employer_id'] ?? null; 
        $consultant_code = $filters['consultant_code'] ?? null;
        $business_unit_code = $filters['business_unit_code'] ?? null;
        $client_code = $filters['client_code'] ?? null;
        $cost_center_code = $filters['code'] ?? null;
        
        $sql_workflow_employer_permissions = $this->get_sql_wf_employer_permissions_cost_centers($employer_id);
        
        $this->db->select([
            'cost_centers.code AS cost_center_code',
            'cost_centers.code AS cost_center_name'
        ]);    
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('(' . $sql_workflow_employer_permissions . ') AS tmp_workflow_employer_permissions', 'tmp_workflow_employer_permissions.cost_center_code=cost_centers.code');
        $this->db->join('tbl_employers employers', 'employers.ID=tmp_workflow_employer_permissions.employer_id AND cost_centers.company_id=employers.company_ID');
        $this->db->where('employers.ID', $employer_id);
        
        if ($consultant_code) {
            $this->db->where('tmp_workflow_employer_permissions.consultant_code', $consultant_code);
        }
        
        if ($business_unit_code) {
            $this->db->where('tmp_workflow_employer_permissions.business_unit_code', $business_unit_code);
        }
        
        if ($client_code) {
            $this->db->where('tmp_workflow_employer_permissions.client_code', $client_code);
        }
        
        if ($cost_center_code) {
            $this->db->where('tmp_workflow_employer_permissions.cost_center_code', $cost_center_code);
        }
        
        $this->db->where('cost_centers.active', 1);

        $this->db->group_by('cost_centers.code');

        return $this->db->get()->result();
    }
    
    public function get_cost_centers(
        $employer_id, 
        $consultant_code, 
        $business_unit_code,
        $client_code
    ) {
        
        $results = $this->get_permission_cost_centers([
            'permission_employer_id' => $employer_id,
            'consultant_code' => $consultant_code,
            'business_unit_code' => $business_unit_code,
            'client_code' => $client_code,
        ]);

        $rows_data = [];

        foreach ($results as $key => $row) {
            $rows_data[] = [
                'COD_CCOSTO' => $row->cost_center_code
            ];
        }

        return [
            'MESSAGE' => 'OK',
            'CENTROCOSTO' => $rows_data
        ];
    }

    /**  INICIO */

    public function add_user($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('tbl_employers', $data);

        return $this->db->insert_id();
    }	


    public function get_app_user($user_id)
    {
        $this->db->from('tbl_employers');
        $this->db->where('ID', $user_id);

        return $this->db->get()->row();
    }

    public function authenticate_by_password($user_id, $password)
    {
        $this->db->from('tbl_employers');
        $this->db->where('ID', $user_id);

        $row = $this->db->get()->row();
        
        if ($row && $this->authenticate($row->email, $password)) {
            return $row;
        }

        return false;
    }

    public function authenticate($user_name, $password)
    {
        $this->db->from('tbl_employers');
        $this->db->where('email', $user_name);

        $row = $this->db->get()->row();

        if ($row && 
            (
                (substr($row->pass_code, 0, 7) == '$2y$10$' && verify_hashing($password, $row->pass_code)) || (substr($row->pass_code, 0, 7) != '$2y$10$' && $row->pass_code == $password)
            )
        ) {
        /* if (needs_rehashing($row->pass_code)) {
                $this->db->where('ID', $row->ID);
                $this->update('tbl_employers', array('pass_code' =>  do_hashing($password)));
            }
    */
            return $row;
        }
        
        return false;
    }

    public function authenticate_by_email($email)
    {
        $this->db->from('tbl_employers');
        $this->db->where('email', $email);

        return $this->db->get()->row();
    }

    public function search_users($company_id, $filters, $per_page, $page)
    {
        $this->db->select('app_users.*');
        $this->db->from('tbl_employers app_users');
        $this->db->where('company_ID', $company_id);

        $query =  trim((string)$filters['query']);

        if ($query != '') {
            $this->db->group_start();
            $this->db->like('app_users.email', $query);
            $this->db->or_like('app_users.first_name', $query);
            $this->db->group_end();
        }

        $this->db->order_by('app_users.ID', 'DESC'); 
        $this->db->limit($per_page, $page);
        
        return $this->db->get()->result();
    }
            
    public function count_search_users($company_id, $filters = array())
    {
        $this->db->from('tbl_employers app_users');
        $this->db->where('company_id', $company_id);

        $query =  trim((string)$filters['query']);

        if ($query != '') {
            $this->db->group_start();
            $this->db->like('app_users.email', $query);
            $this->db->or_like('app_users.first_name', $query);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }	

    public function get_all_active_users($company_id)
    {
        $this->db->select('app_users.*');
        $this->db->from('tbl_employers app_users');
        $this->db->where('company_ID', $company_id);
        $this->db->order_by('app_users.ID', 'DESC'); 

        return $this->db->get()->result();
    }

    public function get_allowed_cost_centers($user_id)
    {
        $this->db->from('tbl_staff_recruiter_client_companies');
        $this->db->where('recruiter_ID', $user_id);
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $row) {
            $list[] = $row->cost_center;
        }

        return $list;
    }

    public function get_allowed_areas($user_id)
    {
        $this->db->from('tbl_employer_permission_internal_areas');
        $this->db->where('user_id', $user_id);
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $row) {
            $list[] = $row->area_id;
        }

        return $list;
    }

    public function get_allowed_job_charges($user_id)
    {
        $this->db->from('tbl_employer_permission_job_charges');
        $this->db->where('user_id', $user_id);
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $row) {
            $list[] = $row->charge_id;
        }

        return $list;
    }

    public function by_profile_id($profile_id)
    {
        $this->db->select('user.*');
        $this->db->from('tbl_employers user');
        $this->db->join('tbl_employer_profiles profiles', 'user.ID=profiles.user_id');
        $this->db->where('profiles.profile_id', $profile_id);
        
        return $this->db->get()->result();
    }
    
    public function get_all_payroll_administrators_by_client($client_code, $company_id)
    {
        $this->db->select([
            'employer.ID AS id',
            'employer.first_name',
            'employer.email',
            'employer.company_ID AS company_id'
        ]);
        $this->db->from('tbl_employers employer');
        $this->db->join('tbl_employer_profiles employer_profiles', 'employer_profiles.user_id=employer.ID');
        $this->db->join('tbl_profile_actions_permissions profile_actions_permissions', 'profile_actions_permissions.employer_id=employer.ID AND employer_profiles.profile_id=profile_actions_permissions.profile_id');
        $this->db->join('tbl_modules_actions modules_actions', 'modules_actions.id=profile_actions_permissions.action_id');
        $this->db->join('tbl_employer_permission_clients client_companies', 'client_companies.employer_id=employer.ID');
        $this->db->where('client_companies.client_code', $client_code);       
        $this->db->where('employer_profiles.profile_id', 3); // Perfil gestor de nomina
        $this->db->where('modules_actions.keyword_id',  'hire_candidates'); // Permiso de contratar candidatos        
        $this->db->where('employer.company_ID', $company_id);
        $this->db->where('employer.sts', 'active');
        
        $this->db->group_by('employer.ID');
        
        return $this->db->get()->result();
    }

    public function get_allowed_user_business_units($user_id)
    {
        $this->db->select('tbl_employer_permission_business_units.business_unit_code');
        $this->db->from('tbl_employer_permission_business_units');
        $this->db->where('employer_id', $user_id);
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $row) {
            $list[] = $row->business_unit_code;
        }

        return $list;
    }

    public function get_allowed_business_units($user)
    {
        $this->db->select('tbl_employer_permission_business_units.business_unit_code as code, tbl_business_units.business_unit_name as name');
        $this->db->from('tbl_employer_permission_business_units');
        $this->db->join('tbl_business_units', 'tbl_employer_permission_business_units.business_unit_code = tbl_business_units.business_unit_code');
        $this->db->where('tbl_employer_permission_business_units.employer_id', $user->ID);
        $this->db->where('tbl_business_units.company_id', $user->company_ID);
        $this->db->group_by('tbl_employer_permission_business_units.business_unit_code,tbl_business_units.business_unit_name');
        $results = $this->db->get()->result();

        return $results;
    }

    public function get_allowed_user_consultants($user)
    {
        $this->db->select('tbl_employer_permission_consultants.consultant_code as code,tbl_workflow_consultants.name');
        $this->db->from('tbl_employer_permission_consultants');
        $this->db->join('tbl_workflow_consultants', 'tbl_employer_permission_consultants.consultant_code = tbl_workflow_consultants.code');
        $this->db->where('tbl_employer_permission_consultants.employer_id', $user->ID);
        $this->db->where('tbl_workflow_consultants.company_id', $user->company_ID);
        $this->db->group_by('tbl_employer_permission_consultants.consultant_code,tbl_workflow_consultants.name');
        $results = $this->db->get()->result();

        return $results;
    }

    public function get_allowed_user_clients($user, $consultants_ids)
    {
        $this->db->select('
            consultants.code AS consultant_code, 
            consultants.name AS consultant_name,
            client_code, 
            tbl_workflow_clients.name'
        );
        $this->db->from('tbl_employer_permission_clients');
        $this->db->join('tbl_workflow_clients', 'tbl_employer_permission_clients.client_code = tbl_workflow_clients.code');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code = tbl_employer_permission_clients.consultant_code');
        $this->db->where('tbl_employer_permission_clients.employer_id', $user->ID);
        $this->db->where('tbl_workflow_clients.company_id', $user->company_ID);
        //$this->db->where_in('tbl_employer_permission_clients.consultant_code', $consultants_ids);
        
        $this->db->group_by([
            'tbl_employer_permission_clients.consultant_code',
            'tbl_employer_permission_clients.client_code'
        ]);
        $results = $this->db->get()->result();

        return $results;
    }

    public function get_allowed_user_cost_centers($user, $client_codes = [], $business_unit_codes = [])
    {
        $this->db->select('
            consultants.code AS consultant_code, 
            consultants.name AS consultant_name, 
            clients.code AS client_code,
            clients.name AS client_name, 
            business_units.business_unit_code,
            business_units.business_unit_name,
            cost_center_code as code'
        );
        $this->db->from('tbl_employer_permission_cost_centers pcc');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=pcc.consultant_code AND consultants.company_id=' . $user->company_ID);
        $this->db->join('tbl_workflow_clients clients', 'clients.code=pcc.client_code AND clients.company_id=' . $user->company_ID);
        $this->db->join('tbl_business_units business_units', 'business_units.business_unit_code=pcc.business_unit_code AND business_units.company_id=' . $user->company_ID);
        //$this->db->where_in("CONCAT(pcc.consultant_code, '__', ppc.pcc.client_code)", $client_codes);
       // $this->db->where_in('pcc.business_unit_code', $business_unit_codes);
        $this->db->where('pcc.employer_id', $user->ID);
        $this->db->group_by([
            'pcc.consultant_code',
            'pcc.client_code',
            'pcc.business_unit_code',
            'pcc.cost_center_code'
        ]);
       
        $results = $this->db->get()->result();

        return $results;
    }

    public function get_all_business_units($company_id) 
    {
        $list = [];
        $this->db->select('tbl_business_units.business_unit_code as code, tbl_business_units.business_unit_name as name');
        $this->db->from('tbl_business_units');
        $this->db->where('tbl_business_units.company_id', $company_id);

        $results = $this->db->get()->result();

        return $results;
    }

    public function get_all_consultants($user) 
    {
        $this->db->select('tbl_workflow_consultants.code, tbl_workflow_consultants.name');
        $this->db->from('tbl_workflow_consultants');
        $this->db->where('tbl_workflow_consultants.company_id', $user->company_ID);
        $consultants = $this->db->get()->result();

        return $consultants;
    }

    public function get_all_clients($user, $consultant_codes = [])
    {
        $this->db->distinct();

        $this->db->select('
            consultants.code AS consultant_code,
            consultants.name AS consultant_name,
            wc.code AS client_code,
            wc.name
        ');

        $this->db->from('tbl_workflow_cost_centers cc');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code = cc.cia_code');
        $this->db->join('tbl_workflow_clients wc', 'wc.code = cc.client_code');
        $this->db->where('cc.company_id', $user->company_ID);
        $this->db->where('wc.active', 1);
        $this->db->where('consultants.active', 1);

        if (!empty($consultant_codes)) {
            $this->db->where_in('cc.cia_code', $consultant_codes);
        }
        
        $this->db->group_by([
            'cc.cia_code', 
            'cc.client_code'
        ]);
        
        return $this->db->get()->result();
    }

    public function  get_all_cost_centers($user, $client_codes = [], $business_unit_codes = [])
    {
        $this->db->distinct();
        $this->db->select('
            consultant.code AS consultant_code,
            consultant.name AS consultant_name,
            cc.code AS code,
            cc.client_code,
            wc.name AS client_name,
            bu.business_unit_code,
            bu.business_unit_name
        ');

        $this->db->from('tbl_workflow_cost_centers cc');
        $this->db->join('tbl_workflow_consultants consultant', 'consultant.code = cc.cia_code AND consultant.company_id=cc.company_id', 'inner');
        $this->db->join('tbl_workflow_clients wc', 'wc.code = cc.client_code AND cc.company_id=wc.company_id', 'inner');
        $this->db->join('tbl_business_units bu', 'bu.business_unit_code = cc.business_unit_code AND bu.company_id=cc.company_id', 'inner');
        
        $this->db->where('cc.active', 1);
        $this->db->where('consultant.active', 1);
        $this->db->where('wc.active', 1);
        $this->db->where('bu.active', 1);
        $this->db->where('cc.company_id', $user->company_ID);

        if (!empty($client_codes)) {
            $this->db->where_in("CONCAT(cc.cia_code, '__', cc.client_code)", $client_codes);
        }
        
        if (!empty($business_unit_codes)) {
            $this->db->where_in('cc.business_unit_code', $business_unit_codes);
        }
        
        $this->db->group_by([
            'cc.cia_code',
            'cc.client_code',
            'cc.business_unit_code',
            'cc.code'
        ]);
       

        return $this->db->get()->result();
    }
    
    public function get_sql_wf_employer_permissions_cost_centers($recruiter_id)
    {
        // $this->db->select([
        //     'pc.employer_id AS employer_id',
        //     'cc.cia_code AS consultant_code',
        //     'cc.client_code AS client_code',
        //     'cc.business_unit_code AS business_unit_code',
        //     'cc.code AS cost_center_code',
        //     '"1" AS origin'
        // ]);
        // $this->db->from('tbl_employer_permission_business_units pc');
        // $this->db->join('tbl_workflow_cost_centers cc', 'cc.business_unit_code=pc.business_unit_code');
        // $this->db->where('cc.active', 1);
        // $this->db->where('pc.employer_id', $recruiter_id);
        // $permission_type_sql1 = $this->db->get_compiled_select();
        
        // $this->db->select([
        //     'pc.employer_id AS employer_id',
        //     'cc.cia_code AS consultant_code',
        //     'cc.client_code AS client_code',
        //     'cc.business_unit_code AS business_unit_code',
        //     'cc.code AS cost_center_code',
        //     '"2" AS origin'
        // ]);
        // $this->db->from('tbl_employer_permission_consultants pc');
        // $this->db->join('tbl_workflow_cost_centers cc', 'cc.cia_code=pc.consultant_code');
        // $this->db->where('cc.active', 1);
        // $this->db->where('pc.employer_id', $recruiter_id);
        
        // $permission_type_sql2 = $this->db->get_compiled_select();
            
        // $this->db->select([
        //     'pc.employer_id AS employer_id',
        //     'cc.cia_code AS consultant_code',
        //     'cc.client_code AS client_code',
        //     'cc.business_unit_code AS business_unit_code',
        //     'cc.code AS cost_center_code',
        //     '"3" AS origin'
        // ]);
        
        // $this->db->from('tbl_employer_permission_clients pc');
        // $this->db->join(
        //     'tbl_workflow_cost_centers cc', 
        //     'cc.cia_code=pc.consultant_code AND cc.client_code=pc.client_code'
        // );
        // $this->db->where('cc.active', 1);
        // $this->db->where('pc.employer_id', $recruiter_id);
        // $permission_type_sql3 = $this->db->get_compiled_select();
            
        $this->db->select([
            'pc.employer_id AS employer_id',
            'cc.cia_code AS consultant_code',
            'cc.client_code AS client_code',
            'cc.business_unit_code AS business_unit_code',
            'cc.code AS cost_center_code',
            '"4" AS origin'
        ]);
        $this->db->from('tbl_employer_permission_cost_centers pc');
        $this->db->join(
            'tbl_workflow_cost_centers cc', 
            'cc.cia_code=pc.consultant_code AND cc.client_code=pc.client_code AND cc.business_unit_code=pc.business_unit_code AND cc.code=pc.cost_center_code'
        );
        $this->db->where('cc.active', 1);
        $this->db->where('pc.employer_id', $recruiter_id);
        
        $permission_type_sql4 = $this->db->get_compiled_select();
        
        $sql = "SELECT 
            workflow_ep.employer_id,
            workflow_ep.consultant_code, 
            workflow_ep.client_code, 
            workflow_ep.business_unit_code, 
            workflow_ep.cost_center_code, 
            workflow_ep.origin 
            FROM (($permission_type_sql4)) AS workflow_ep 
                
            where workflow_ep.origin = (
                SELECT sub.origin from (($permission_type_sql4)) as sub 
                ORDER BY (
                    CASE 
                    WHEN sub.origin = '1' THEN 1 
                    WHEN sub.origin = '2' THEN 2 
                    WHEN sub.origin = '3' THEN 3 
                    WHEN sub.origin = '4' THEN 4 
                    END
                ) DESC
                LIMIT 1
            )";
        return $sql;
    }
}
