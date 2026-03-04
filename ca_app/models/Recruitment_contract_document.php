<?php

class Recruitment_contract_document extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_recruitment_contract_documents');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_contract_documents');
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
