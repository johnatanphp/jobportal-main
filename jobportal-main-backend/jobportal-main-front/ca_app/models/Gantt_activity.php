<?php
class Gantt_activity extends CI_Model {
    
	public function get_active_activities()
    {
        $this->db->from('tbl_gantt_activities');

        return $this->db->get()->result();
    }
}
