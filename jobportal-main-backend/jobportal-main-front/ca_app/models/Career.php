<?php

class Career extends CI_Model
{
    protected $table = 'tbl_careers';
    protected $primary_key = 'id';
    
    public function add($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
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

    public function get_all_by_institution_code($institution_code)
    {
        $this->db->select([
            'careers.code',
            'careers.name'
        ]);
        $this->db->from('tbl_careers careers');
        $this->db->join('tbl_institution_careers ic', 'ic.career_code=careers.code');  
        $this->db->join('tbl_institutions institutions', 'ic.institution_code=institutions.code');    
        $this->db->where('careers.active', 1);
        $this->db->where('institutions.active', 1);
        $this->db->where('ic.institution_code', $institution_code);
    
        return $this->db->get()->result();
    }
}
