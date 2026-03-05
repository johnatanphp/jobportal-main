<?php

class Staff_request_type_reason extends CI_Model
{
    protected $table = 'tbl_staff_request_type_reasons';
    protected $primary_key = 'id';
    
    public function add($data)
    {
        $status = $this->db->insert($this->table, $data);

        return $status ? $data[$this->primary_key] : $status;
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where($this->primary_key, $id_or_where);
        }

        return $this->db->update($this->table, $data);
    }
    
    public function find($id_or_where)
    {
        $this->db->from($this->table);

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where($this->primary_key, $id_or_where);
        }

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from($this->table);

        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }

    public function delete($id_or_where)
    {
        if (is_array($id_or_where) && count($id_or_where) == 0) {
            return false;
        }

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where($this->primary_key, $id_or_where);
        }
        
        return $this->db->delete($this->table);
    }
}
