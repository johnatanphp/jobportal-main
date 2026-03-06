<?php
class Internal_area extends CI_Model {

	public function all($where = [])
    {
        $this->db->from('tbl_internal_areas');
        $this->db->where($where);
        return $this->db->get()->result();
    }

	public function get_all_active_areas()
	{
		$this->db->from('tbl_internal_areas');
		return $this->db->get()->result();
	}

	public function get_area_by_id($area_id)
	{
		$this->db->from('tbl_internal_areas');
		$this->db->where('ID', $area_id);

		$Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }

        $Q->free_result();
        return $return;
	}
}
