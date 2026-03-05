<?php

class Seeker_immigration_record extends CI_Model
{
    public function find($id_or_where)
    {
        $this->db->from('tbl_seeker_immigration_records');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->get()->row();
    }

    public function create($data)
    {
        $this->db->insert('tbl_seeker_immigration_records', $data);

        return $this->db->insert_id();
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->update('tbl_seeker_immigration_records', $data);
    }

    public function delete($id_or_where)
    {
        if (is_array($id_or_where) && count($id_or_where) == 0) {
            return false;
        }

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }
        
        return $this->db->delete('tbl_seeker_immigration_records');
    }

    public function all($where = [])
    {
        $this->db->from('tbl_seeker_immigration_records');

        if (count($id_or_where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }

    public function create_or_update($where, $data)
    {
        $record = $this->find($where);

        if ($record) {
            return $this->update($where, $data);
        } else {
            return $this->create($data);
        }
    }
}
