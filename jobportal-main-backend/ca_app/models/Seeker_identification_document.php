<?php

class Seeker_identification_document extends CI_Model
{
    public function find($id_or_where)
    {
        $this->db->from('tbl_seeker_identification_documents');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->get()->row();
    }
}
