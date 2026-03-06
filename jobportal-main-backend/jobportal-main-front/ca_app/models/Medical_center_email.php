<?php

class Medical_center_email extends CI_Model
{
    //protected $table = 'tbl_medical_center_emails';
    //protected $primary_key = 'id';

    public function search_by($filters)
    {
        return $this->db->get_where('tbl_medical_center_emails', $filters)
               ->result();
    }
    
    public function get_emails_by_code($code)
    {
    	$this->db->from('tbl_medical_center_emails');
    	$this->db->where('code', $code);

    	$results = $this->db->get()->result();
    	
    	$emails = [];

    	foreach ($results as $key => $row) {
    		$emails[] = $row->email;
    	}

    	return $emails;
    }

    public function create($data)
    {
        $this->db->insert('tbl_medical_center_emails', $data);

        return $this->db->insert_id();
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->update('tbl_medical_center_emails', $data);
    }
    
    public function find($id)
    {
        $this->db->from('tbl_medical_center_emails');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_medical_center_emails');

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
            $this->db->where('id', $id_or_where);
        }
        
        return $this->db->delete('tbl_medical_center_emails');
    }
}
