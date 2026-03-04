<?php
class Factor_valuation extends CI_Model
{	
	private $table_name = 'tbl_factor_valuations';
	
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
		$this->db->where('id', $id);
		$return=$this->db->update($this->table_name, $data);
		return $return;
	}
	
	public function delete($id)
    {
		$this->db->where('id', $id);
		$this->db->delete($this->table_name);
	}
	
	public function find($id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->where('id', $id);

        return $this->db->get()->row();
    }

	public function get_all_records($active = null)
    {    
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->order_by("id", "ASC");
        
        if (!is_null($active)) {
            $this->db->where('active', $active ? 1 : 0);
        }

        return $this->db->get()->result();
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
}
