<?php
class Job_charge extends CI_Model 
{	
	public function find($charge_id)
    {    
	    $this->db->from('tbl_job_charges');
	    $this->db->where('ID', $charge_id);
	    return $this->db->get()->row();
    }

	public function get_all_active()
    {    
	    $this->db->select('*');
	    $this->db->from('tbl_job_charges');
	    $this->db->where('sts', 'active');

		$this->db->order_by('charge_name', 'ASC');
	 
		return $this->db->get()->result();
    }

	public function get_all()
    {    
	    $this->db->select('*');
	    $this->db->from('tbl_job_charges');
		$this->db->order_by('charge_name', 'ASC');
	 
		return $this->db->get()->result();
    }

	public function get_all_active_job_charges()
    {    
	    $this->db->select('*');
	    $this->db->from('tbl_job_charges');
	    $this->db->where('sts', 'active');

		$this->db->order_by('charge_name', 'ASC');
	 
	    $Q = $this->db->get();
	 
	    if ($Q->num_rows() > 0) {
	        $return = $Q->result();
	    } else {
	        $return = [];
	    }

	    $Q->free_result();
	    return $return;
    }

    public function get_job_charge_by_id($charge_id)
    {    
	    $this->db->from('tbl_job_charges');
	    $this->db->where('ID', $charge_id);
	    $Q = $this->db->get();
	 
	    if ($Q->num_rows() > 0) {
	        $return = $Q->row();
	    } else {
	        $return = 0;
	    }

	    $Q->free_result();
	    return $return;
    }

	public function all($where = [])
    {
        $this->db->from('tbl_job_charges');
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
