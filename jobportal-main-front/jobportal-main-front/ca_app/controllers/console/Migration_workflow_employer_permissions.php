<?php
require_once ("App_console.php");

class Migration_workflow_employer_permissions extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $limit = 80;
        $this->migrate_business_units($limit);
        $this->migrate_consultants($limit);
        $this->migrate_clients($limit);
        $this->migrate_cost_center($limit);
    }

    public function migrate_business_units($limit)
    {
        $this->db->select([
            'pcv.cod_business_unit as business_unit_code',
            'pcv.recruiter_ID AS employer_id',
            'pcn.business_unit_code AS pvn_id'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies pcv');
        $this->db->join('tbl_employers e', 'e.ID=pcv.recruiter_ID');
        $this->db->join(
            'tbl_employer_permission_business_units pcn', 
            'pcn.employer_id=pcv.recruiter_ID AND pcn.business_unit_code=pcv.cod_business_unit',
            'left'
        );
        $this->db->group_by(['pcv.recruiter_ID', 'pcv.cod_business_unit']);
        $this->db->having('pvn_id IS NULL');
        
        if ($limit) {
            $this->db->limit($limit);
        }
    
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
        
            try {   
                $this->db->insert('tbl_employer_permission_business_units', [
                    'business_unit_code' => $row->business_unit_code,
                    'employer_id' => $row->employer_id
                ]);
            } catch( Exception $e) {
            
            }
        }
    }
    
    public function migrate_consultants($limit)
    {
        $this->db->select([
            'pcv.no_cia as consultant_code',
            'pcv.cod_clie as client_code',
            'pcv.recruiter_ID AS employer_id',
            'pcn.consultant_code AS pvn_id'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies pcv');
        $this->db->join('tbl_employers e', 'e.ID=pcv.recruiter_ID');
        $this->db->join(
            'tbl_employer_permission_consultants pcn', 
            'pcn.employer_id=pcv.recruiter_ID AND pcn.consultant_code=pcv.no_cia',
            'left'
        );
        $this->db->group_by(['pcv.recruiter_ID', 'pcv.no_cia']);
        $this->db->having('pvn_id IS NULL');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
            try {
                $this->db->insert('tbl_employer_permission_consultants', [
                    'consultant_code' => $row->consultant_code,
                    'employer_id' => $row->employer_id
                ]);
            } catch( Exception $e) {
            
            }

        }
    }
    
    public function migrate_clients($limit)
    {
        $this->db->select([
            'pcv.no_cia as consultant_code',
            'pcv.cod_clie as client_code',
            'pcv.recruiter_ID AS employer_id',
            'pcn.client_code AS pvn_id'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies pcv');
        $this->db->join('tbl_employers e', 'e.ID=pcv.recruiter_ID');
        $this->db->join(
            'tbl_employer_permission_clients pcn', 
            'pcn.employer_id=pcv.recruiter_ID AND pcn.consultant_code=pcv.no_cia AND pcn.client_code=pcv.cod_clie',
            'left'
        );
        $this->db->group_by(['pcv.recruiter_ID', 'pcv.no_cia', 'pcv.cod_clie']);
        $this->db->having('pvn_id IS NULL');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
            $this->db->insert('tbl_employer_permission_clients', [
                'consultant_code' => $row->consultant_code,
                'client_code' => $row->client_code,
                'employer_id' => $row->employer_id
            ]);
        }
    }
    
    public function migrate_cost_center($limit)
    {
        $this->db->select([
            'pcv.no_cia as consultant_code',
            'pcv.cod_clie as client_code',
            'pcv.cod_business_unit as business_unit_code',
            'pcv.cost_center as cost_center_code',
            'pcv.recruiter_ID AS employer_id',
            'pcn.id AS pvn_id'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies pcv');
        $this->db->join('tbl_employers e', 'e.ID=pcv.recruiter_ID');
        $this->db->join(
            'tbl_employer_permission_cost_centers pcn', 
            'pcn.employer_id=pcv.recruiter_ID AND consultant_code=pcv.no_cia AND pcn.client_code=pcv.cod_clie AND pcn.business_unit_code=pcv.cod_business_unit AND pcn.cost_center_code=pcv.cost_center',
            'left'
        );
        $this->db->having('pvn_id IS NULL');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
            $this->db->insert('tbl_employer_permission_cost_centers', [
                'consultant_code' => $row->consultant_code,
                'client_code' => $row->client_code,
                'business_unit_code' => $row->business_unit_code,
                'cost_center_code' => $row->cost_center_code,
                'employer_id' => $row->employer_id
            ]);
        }
    }
}
