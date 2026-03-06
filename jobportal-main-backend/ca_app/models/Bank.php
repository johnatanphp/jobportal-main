<?php
class Bank extends CI_Model {
	
    public function __construct() {
	   $this->load->database();
    }

 	public function get_all_banks()
 	{
 		$this->db->from('tbl_banks');
 		return $this->db->get()->result();
 	}

	public function get_by_bank_name($bank_name)
	{
		$this->db->from('tbl_banks');
		$this->db->where('bank_name', $bank_name);
		return $this->db->get()->row();
	}
}