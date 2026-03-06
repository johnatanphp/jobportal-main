<?php
class Staff_request_assignment extends CI_Model
{
    public function find($id_or_where)
    {   
        $this->db->from('tbl_staff_request_assignments');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->get()->row();
    }

    public function add($data)
    {
        $this->db->insert('tbl_staff_request_assignments', $data);
        return $this->db->insert_id();
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }
        return $this->db->update('tbl_staff_request_assignments', $data);
    }

    public function delete($id_or_where)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->delete('tbl_staff_request_assignments');
    }

    public function all($where)
    {
        $this->db->from('tbl_staff_request_assignments');
    
        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();    
    }
}
