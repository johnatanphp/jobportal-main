<?php
class Screening_type extends CI_Model 
{
	public function find($id)
    {
        $this->db->from('tbl_screening_types');
		$this->db->where('id', $id);
        return $this->db->get()->row();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_screening_types');

		if (count($where) > 0) {
			$this->db->where($where);
		}

    	return $this->db->get()->result();
	}
}
