<?php
class Work_experience extends CI_Model
{	
	private $table_name = 'tbl_work_experiences';
	
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
	
	public function find($where_or_id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);

        if (is_array($where_or_id)) {
            $this->db->where($where_or_id);
        } else {
            $this->db->where('id', $where_or_id);
        }

        return $this->db->get()->row();
    }

    public function get_all($where = [])
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
		
        if (count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->rresult();        
    }

	public function get_all_records($active = null)
    {    
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->order_by("id", "ASC");
        
        if (!is_null($active)) {
            $this->db->where('active', $active ? 1 : 0);
        }

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
        $this->db->order_by("id", "ASC");
        
        return $this->db->get()->result();
    }
	
	public function record_count($table_name)
    {
		return $this->db->count_all($table_name);
    }

    public function find_by_code($code)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->where('code', $code);
        return $this->db->get()->row();
    }
}
