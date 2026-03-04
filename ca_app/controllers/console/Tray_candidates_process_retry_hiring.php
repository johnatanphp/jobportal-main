<?php
require_once ("App_console.php");

class Tray_candidates_process_retry_hiring extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->load->library(
            'WS_overall/WS_overall_recruitment_tray_seeker_hire_lib', 
            null, 
            'WS_overall_recruitment_tray_seeker_hire_lib'
        );
        $this->db->select([
            'tray_candidates.id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->from('tbl_recruitment_contracts contracts', 'tray_candidates.job_id=tray_candidates.job_id AND tray_candidates.seeker_id=contracts.seeker_id');
        $this->db->where('tray_candidates.status_id', 5); //FALLIDO
        $this->db->limit(5);
        $this->db->order_by('contracts.synchronization_attempts', 'ASC');
        $result = $this->db->get()->result();
   
        foreach ($result as $tray) {
            $this->WS_overall_recruitment_tray_seeker_hire_lib->send($tray->id);
        }
    }
}
