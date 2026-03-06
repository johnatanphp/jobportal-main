<?php

class Medical_center_location extends CI_Model
{
    //protected $table = 'tbl_medical_center_locations';
    //protected $primary_key = 'id';

    public function create($data)
    {
        $this->db->insert('tbl_medical_center_locations', $data);

        return $this->db->insert_id();
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->update('tbl_medical_center_locations', $data);
    }
    
    public function find($id)
    {
        $this->db->from('tbl_medical_center_locations');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_medical_center_locations');

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }
}
