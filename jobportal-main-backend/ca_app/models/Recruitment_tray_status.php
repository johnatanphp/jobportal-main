<?php
class Recruitment_tray_status extends CI_Model
{		

    public function find($where = [])
    {
        $this->db->from('tbl_recruitment_tray_status');
        
        if (count($where) > 0) {
            $this->db->where($where);
        }
    
        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_tray_status');
        
        if (count($where) > 0) {
            $this->db->where($where);
        }
    
        return $this->db->get()->result();
    }
}
