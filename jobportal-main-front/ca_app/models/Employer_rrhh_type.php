<?php
class Employer_rrhh_type extends CI_Model 
{    
    public function find($where = [])
    {
        $this->db->from('tbl_employer_rrhh_types');
        $this->db->where($where);
        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_employer_rrhh_types');
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
