<?php
class Workflow_client extends CI_Model
{	
	private $table_name = 'tbl_workflow_clients';
	
    public function find($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        
        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        return $this->db->get()->result();
    }

    public function get_all($company_id, $cia_code, $uni_neg_code)
    {
        $this->db->select([
            "wc.code AS COD_CLIE", 
            "wc.name AS CLIENTE"
        ]);
        $this->db->from('tbl_workflow_cost_centers wcc');
        $this->db->join('tbl_workflow_clients wc', 'wcc.client_code=wc.code AND wcc.company_id=wc.company_id');
        $this->db->where('wcc.company_id', $company_id);
        $this->db->where('wcc.active', 1);
        $this->db->where('wc.active', 1);
        
        $this->db->where('wcc.cia_code', $cia_code);
        $this->db->where('wcc.business_unit_code', $uni_neg_code);

        $this->db->order_by('wc.name', 'ASC');

        $this->db->group_by('wcc.client_code');

        return $this->db->get()->result();
    }
}