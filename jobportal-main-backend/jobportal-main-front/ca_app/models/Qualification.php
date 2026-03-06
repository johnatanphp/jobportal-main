<?php
class Qualification extends CI_Model {
	
	private $table_name = 'tbl_qualifications';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        
        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }

	public function add($data)
    {
        $return = $this->db->insert($this->table_name, $data);
        if ((bool) $return === TRUE) {
            return $this->db->insert_id();
        } else {
            return $return;
        }		
	}	
	
	public function update($id, $data)
    {
		$this->db->where('ID', $id);
		$return=$this->db->update($this->table_name, $data);
		return $return;
	}
	
	public function delete($id)
    {
		$this->db->where('ID', $id);
		$this->db->delete($this->table_name);
	}
	
    public function find($id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->where('ID', $id);
        return $this->db->get()->row();
    }

	public function get_record_by_id($id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->where('ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row_array();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

	public function get_all_records()
    {    
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->order_by("ID", "ASC");
        
        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }

    public function search($per_page, $page)
    {    
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->limit($per_page, $page);
        $this->db->order_by("ID", "ASC");
        
        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
	
	public function record_count($table_name)
    {
		return $this->db->count_all($table_name);
    }

    public function get_all_records_by_val($val)
    {    
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('val', $val);
        $this->db->order_by("ID", "ASC");
        
        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
}
