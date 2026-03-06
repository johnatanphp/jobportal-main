<?php
class Workflow_cost_center extends CI_Model
{	
	private $table_name = 'tbl_workflow_cost_centers';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        return $this->db->get()->result();
    }

    public function get_all(
        $company_id, 
        $cia_code = '', 
        $client_code = '', 
        $business_unit_code = ''
    )
    {
        $this->db->select([
            "wcc.code AS COD_CCOSTO"
        ]);
        $this->db->from('tbl_workflow_cost_centers wcc');
        $this->db->where('wcc.company_id', $company_id);
        $this->db->where('wcc.active', 1);

        if ($cia_code != '') {
            $this->db->where('wcc.cia_code', $cia_code);
        }
        
        if ($client_code != '') {
            $this->db->where('wcc.client_code', $client_code);
        }

        if ($business_unit_code != '') {
            $this->db->where('wcc.business_unit_code', $business_unit_code);
        }

        $this->db->group_by('wcc.code');

        return $this->db->get()->result();
    }
}
