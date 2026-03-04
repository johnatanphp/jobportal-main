<?php
class Risk_criteria extends CI_Model
{	
	private $table_name = 'tbl_risk_criteria';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        return $this->db->get()->result();
    }
}