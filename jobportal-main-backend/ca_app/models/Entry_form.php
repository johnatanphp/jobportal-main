<?php

class Entry_form extends CI_Model
{
    public function find($where = [])
    {
        $this->db->from('tbl_entry_form');

        if (count($where) > 0) {
            $this->db->where($where);
        }
        
        return $this->db->get()->row();
    }
}
