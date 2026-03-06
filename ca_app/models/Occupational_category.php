<?php
class Occupational_category extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_occupational_categories');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    public function get_all($where = [])
    {
        $this->db->from('tbl_occupational_categories');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
