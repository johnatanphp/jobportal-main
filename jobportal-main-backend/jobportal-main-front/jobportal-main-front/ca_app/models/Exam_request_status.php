<?php
class Exam_request_status extends CI_Model 
{
	public function find($id)
    {
        $this->db->from('tbl_exam_request_status');
		$this->db->where('id', $id);
        return $this->db->get()->row();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_exam_request_status');

		if (count($where) > 0) {
			$this->db->where($where);
		}

        $this->db->order_by('order', 'ASC');

    	return $this->db->get()->result();
	}
}
