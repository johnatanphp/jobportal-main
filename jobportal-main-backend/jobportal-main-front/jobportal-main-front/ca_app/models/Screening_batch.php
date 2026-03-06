<?php
class Screening_batch extends CI_Model 
{
	public function find($where = [])
    {
        $this->db->from('tbl_screening_batch');
		$this->db->where($where);
        return $this->db->get()->row();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_screening_batch');

		if (count($where) > 0) {
			$this->db->where($where);
		}
    	return $this->db->get()->result();
	}
}
