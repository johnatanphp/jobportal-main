<?php

class Exam_request_overall_email extends CI_Model
{
    //protected $table = 'tbl_medical_center_emails';
    //protected $primary_key = 'id';

    public function create($data)
    {
        $this->db->insert('tbl_exam_request_overall_emails', $data);

        return $this->db->insert_id();
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->update('tbl_exam_request_overall_emails', $data);
    }
    
    public function find($id)
    {
        $this->db->from('tbl_exam_request_overall_emails');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_exam_request_overall_emails');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }

    public function delete($id_or_where)
    {
        if (is_array($id_or_where) && count($id_or_where) == 0) {
            return false;
        }

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }
        
        return $this->db->delete('tbl_exam_request_overall_emails');
    }
}
