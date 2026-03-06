<?php
class Recruitment_tray_screening_batch extends CI_Model 
{	
	public function find($where = [])
    {    
	    $this->db->from('tbl_recruitment_tray_screening_batches');
        
        if (count($where) > 0) {
            $this->db->where($where);
        }
        
	    return $this->db->get()->row();
    }
}
