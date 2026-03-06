<?php

class Exam_request_result_type extends CI_Model
{
    //protected $table = 'tbl_exam_request_result_types';
    //protected $primary_key = 'id';

    public function find($id)
    {
        $this->db->from('tbl_exam_request_result_types');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function get_options($exam_type_id, $resource_type = null)
    {
    	$this->db->from('tbl_exam_request_result_types');

    	$this->db->where('exam_type_id', $exam_type_id);
    	//$this->db->where('resource_type', $resource_type);

        $this->db->group_by('result_name');
    	
    	return $this->db->get()->result();
    }

    public function get_options_by_exam_type(
        $result 
    )
    {
        $exam_type_id = $result->exam_type_id;

        $resource_type = null;

        if ($exam_type_id == 2) {
        	$resource_type = $result->type;	
        }

        return $this->get_options($exam_type_id, $resource_type);
    }
}