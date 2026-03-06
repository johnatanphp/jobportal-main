<?php
class Laboral_benefit extends CI_Model 
{
	public function find($id)
    {
        $this->db->from('tbl_laboral_benefits');
		$this->db->where('ID', $id);
        return $this->db->get()->row();
    }

	public function get_benefit_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_laboral_benefits');
		$this->db->where('ID', $id);
        $Q = $this->db->get();
    
        if ($Q->num_rows() > 0) {
            return $Q->row();
        }

        return 0;
    }

    public function add_benefit($data){
  
	    if ($this->db->insert('tbl_laboral_benefits', $data)) {
	        return $this->db->insert_id();
	    }

	    return false;       	
	}

	public function update_benefit($id, $data)
	{
		$this->db->where('ID', $id);
		return $this->db->update('tbl_laboral_benefits', $data);
	}
	
	public function delete_benefit($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete('tbl_laboral_benefits');
	}

    public function record_count($table_name) {
		return $this->db->count_all($table_name);
    }

    public function get_all_records($filters = [])
    {
    	$this->db->from('tbl_laboral_benefits');
    	$this->db->order_by('benefit_name', 'ASC');

		if (isset($filters['company_id'])) {
			$this->db->where('company_id', $filters['company_id']);
		}
		
    	return $this->db->get()->result();
    }

    public function search_laboral_benefits()
    {
    	$this->db->from('tbl_laboral_benefits');
    	$this->db->order_by('benefit_name', 'ASC');

    	return $this->db->get()->result();
    }

	public function all($where = [])
    {
    	$this->db->from('tbl_laboral_benefits');

		if (count($where) > 0) {
			$this->db->where($where);
		}

    	return $this->db->get()->result();
	}
}
