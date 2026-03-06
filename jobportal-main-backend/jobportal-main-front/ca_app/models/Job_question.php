<?php
class Job_question extends CI_Model {

    public function __construct() {
	   $this->load->database();
    }

    public function get_question_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_job_questions');
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
}
