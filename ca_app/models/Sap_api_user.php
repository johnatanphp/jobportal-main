<?php
class Sap_api_user extends CI_Model 
{
	public function find($where = [])
    {
        $this->db->from('tbl_sap_api_users');

        if (count($where) > 0) {
			$this->db->where($where);
		}

        return $this->db->get()->row();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_sap_api_users');

		if (count($where) > 0) {
			$this->db->where($where);
		}

    	return $this->db->get()->result();
	}
}
