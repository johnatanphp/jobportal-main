<?php
class Job_competence extends CI_Model {
    public function __construct() {
	   $this->load->database();
    }

    public function get_active_competences_by_job_charge_id($job_charge_id)
    { 
    	$this->db->from('tbl_job_competences competences');
    	$this->db->join('tbl_job_charges_job_competences charges_competences', 'competences.ID=charges_competences.job_competence_ID');
    	$this->db->where('charges_competences.job_charge_ID', $job_charge_id);
    	$this->db->where('competences.sts', 'active');
    	
        $this->db->order_by('competences.competence_name', 'ASC');
	 
	    return $this->db->get()->result();
    }
}