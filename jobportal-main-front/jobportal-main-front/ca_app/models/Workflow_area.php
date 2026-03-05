<?php
class Workflow_area extends CI_Model
{	
	private $table_name = 'tbl_workflow_areas';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
