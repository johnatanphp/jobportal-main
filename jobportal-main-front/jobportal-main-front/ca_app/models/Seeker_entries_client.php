<?php
class Seeker_entries_client extends CI_Model 
{	
    public function all($where = [])
    {
        $this->db->from('tbl_seeker_entries_clients');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
