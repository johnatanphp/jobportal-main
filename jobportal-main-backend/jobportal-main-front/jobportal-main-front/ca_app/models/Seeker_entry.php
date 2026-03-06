<?php
class Seeker_entries_job extends CI_Model 
{	
    public function find_by_entry_key($entry_key)
    {
        $this->db->from('tbl_seeker_entries_jobs');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
