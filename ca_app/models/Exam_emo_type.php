<?php
class Exam_emo_type extends CI_Model 
{
	public function find($id)
    {
        $this->db->from('tbl_exam_emo_types');
		$this->db->where('id', $id);
        return $this->db->get()->row();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_exam_emo_types');

		if (count($where) > 0) {
			$this->db->where($where);
		}

    	return $this->db->get()->result();
	}
}
