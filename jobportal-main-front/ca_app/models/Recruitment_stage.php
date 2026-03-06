<?php
class Recruitment_stage extends CI_Model {

    public function find($where = [])
    {
        $this->db->from('tbl_recruitment_stages');

        if (count($where) > 0) {
            $this->db->where($where);
        }
        
        return $this->db->get()->row();      
    }

    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_stages');

        if (count($where) > 0) {
            $this->db->where($where);
        }
        
        return $this->db->get()->result();      
    }
}
