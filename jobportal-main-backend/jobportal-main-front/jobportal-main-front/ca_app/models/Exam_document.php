<?php

class Exam_document extends CI_Model
{
    //protected $table = 'tbl_exam_request_types';
    //protected $primary_key = 'id';

    public function find($id)
    {
        $this->db->from('tbl_exam_request_types');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }
}
