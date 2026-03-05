<?php
class Hrmgo_api_conection extends CI_Model 
{
	public function find($where = [])
    {
        $this->db->from('tbl_hrmgo_api_conections');

        if (count($where) > 0) {
			$this->db->where($where);
		}

        return $this->db->get()->row();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_hrmgo_api_conections');

		if (count($where) > 0) {
			$this->db->where($where);
		}

    	return $this->db->get()->result();
	}
}
