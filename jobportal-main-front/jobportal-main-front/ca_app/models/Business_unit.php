<?php
class Business_unit extends CI_Model
{	
	private $table_name = 'tbl_business_units';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        return $this->db->get()->result();
    }
}