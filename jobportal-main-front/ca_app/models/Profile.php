<?php
class Profile extends CI_Model
{	
	private $table_name = 'tbl_profiles';
	
    public function find($id_or_where)
    {
        $this->db->from($this->table_name);
        
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->get()->row();
    }
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        
        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
