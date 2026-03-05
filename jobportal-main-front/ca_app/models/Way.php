<?php
class Way extends CI_Model
{	
	private $table_name = 'tbl_ways';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        
        if (count($where) > 0) {
            $this->db->where($where);
        }
    
        return $this->db->get()->result();
    }
}
