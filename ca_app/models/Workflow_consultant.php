<?php
class Workflow_consultant extends CI_Model
{	
	private $table_name = 'tbl_workflow_consultants';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        return $this->db->get()->result();
    }

    public function get_all($company_id)
    {
        $this->db->select([
            "wc.code AS NO_CIA", 
            "wc.name AS CONSULTORA"
        ]);
        $this->db->from('tbl_workflow_cost_centers wcc');
        $this->db->join('tbl_workflow_consultants wc', 'wcc.cia_code=wc.code AND wcc.company_id=wc.company_id');
        $this->db->where('wcc.company_id', $company_id);
        $this->db->where('wcc.active', 1);
        $this->db->where('wc.active', 1);

        $this->db->order_by('wc.name', 'ASC');
        
        $this->db->group_by('wcc.cia_code');

        return $this->db->get()->result();
    }

    public function get_consultant_by_cost_center($cost_center, $company_id = 1) 
    {
        $this->db->select([
            'consultant.code AS consultant_code',
            'consultant.name AS consultant_name'
        ]);
        $this->db->from('tbl_workflow_cost_centers cost_center');
        $this->db->join('tbl_workflow_consultants consultant', 'consultant.code=cost_center.cia_code AND consultant.company_id="' . $company_id . '"');
        $this->db->where('cost_center.code', $cost_center);
        $this->db->where('cost_center.company_id', $company_id);
        $this->db->group_by('consultant.code');
        
        return $this->db->get()->row();
    }
}
