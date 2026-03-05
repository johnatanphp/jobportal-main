<?php
class Jobseeker_academic extends CI_Model 
{	
	private $table_name = 'tbl_seeker_academic';
	
    public function find($id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('ID', $id);

        return $this->db->get()->row();
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
	
	public function update($id, $data){
		$this->db->where('ID', $id);
		$return=$this->db->update($this->table_name, $data);
		return $return;
	}
	
	public function delete($id){
		$this->db->where('ID', $id);
		$this->db->delete($this->table_name);
	}
	
	public function get_record_by_id($id) {
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

	public function get_all_records() {
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
	
	public function get_record_by_seeker_id($seeker_id) {
        $this->db->select('degree_title, institude, city');
        $this->db->from($this->table_name);
		$this->db->where('seeker_ID', $seeker_id);
		$this->db->order_by('end_date', 'DESC');
		$this->db->limit('1');
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function record_count($table_name) {
		return $this->db->count_all($table_name);
    }

    public function save_certificate($study_id, $seeker_id, $certificate)
    {
        $this->db->where('ID', $study_id);
        $this->db->where('seeker_ID', $seeker_id);
        
        $result = $this->db->update($this->table_name, array('attached_certificate' => $certificate));

        return $result;
    }
}