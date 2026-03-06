<?php
class Seeker_entries_job extends CI_Model 
{	
    public function all($where = [])
    {
        $this->db->from('tbl_seeker_entries_jobs');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
