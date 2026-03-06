<?php
class Recruitment_document_type extends CI_Model 
{
    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_document_types');
        $this->db->where($where);
        return $this->db->get()->result();
    }

    public function find($id)
    {
        $this->db->from('tbl_recruitment_document_types');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function find_by_key($key)
    {
        $this->db->from('tbl_recruitment_document_types');
        $this->db->where('key', $key);

        return $this->db->get()->row();
    }
}
