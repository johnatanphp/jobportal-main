<?php
class Institute extends CI_Model {
    public function __construct() {
	   $this->load->database();
    }
    
	public function add($data){
  
            $return = $this->db->insert('tbl_institute', $data);
            if ((bool) $return === TRUE) {
                return $this->db->insert_id();
            } else {
                return $return;
            }       
	}	
	
	public function update($id, $data){
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_institute', $data);
		return $return;
	}
	
	public function delete($id){
		$this->db->where('ID', $id);
		$this->db->delete('tbl_institute');
	}
	public function get_all_records($per_page, $page) {
        $this->db->select('*');
        $this->db->from('tbl_institute');
        $this->db->limit($per_page, $page);

		$this->db->order_by("name", "ASC");
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
	
	public function record_count($table_name) {
		return $this->db->count_all($table_name);
    }
	
	public function get_institute_by_id($id) {
        $this->db->select('*');
        $this->db->from('tbl_institute');
		$this->db->where('ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

    public function get_all_actives() {
        
        $this->db->select('*');
        $this->db->from('tbl_institute');
        $this->db->where('sts', null);
        $this->db->or_where('sts', 'active');

        $this->db->order_by("name", "ASC");
        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }

        $Q->free_result();
        
        return $return;
    }

    public function search_suggestions($search, $limit = 20)
    {    
        $this->db->select('*');
        $this->db->from('tbl_institute');
        $this->db->like('name', $search);
        $this->db->where('sts', 'active');
        $this->db->limit($limit);

        $this->db->order_by("name", "ASC");
        
        $result = $this->db->get();
        $suggestions = array();
       
        foreach($result->result() as $row) {
            $data = array(
                'value' => $row->name,
            );

            $suggestions[] = $data;
        }

        $result->free_result();
        
        return $suggestions;
    }
}
