<?php
class Sunat_code extends CI_Model
{
    public function find($id)
    {
        return $this->get_sunat_code_by_id($id);
    }
	
	public function get_sunat_code_by_id($id)
    {
        $this->db->select('tbl_sunat_codes.*');
        $this->db->from('tbl_sunat_codes');
		$this->db->where('tbl_sunat_codes.id', $id);

        return $this->db->get()->row();
    }
	
	public function get_sunat_code_by_code($code)
    {
        $this->db->select('tbl_sunat_codes.*');
        $this->db->from('tbl_sunat_codes');
		$this->db->where('tbl_sunat_codes.code', $code);

        return $this->db->get()->row();
    }

    public function get_all_sunat_code()
    {
        $this->db->select('tbl_sunat_codes.*');
        $this->db->from('tbl_sunat_codes');

        return $this->db->get()->result();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_sunat_codes');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
