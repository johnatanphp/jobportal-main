<?php
class Gantt_type extends CI_Model
{    
    public function find($id_or_filter)
    {
        $this->db->from('tbl_gantt_types');

        if (is_array($id_or_filter)) {
            $this->db->where($id_or_filter);
        } else {
            $this->db->where('id', $id_or_filter);
        }

        return $this->db->get()->row();
    }

    public function all($filter = [])
    {
        $this->db->from('tbl_gantt_types');

        if (is_array($filter) && count($filter) > 0) {
            $this->db->where($id_or_filter);
        }

        return $this->db->get()->result();
    }

	public function get_gangtt_types()
    {
        $this->db->from('tbl_gantt_types');

        return $this->db->get()->result();
    }
}
