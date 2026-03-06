<?php
require_once ("App_console.php");

class Notify_entry_jobseekers extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->load->library(
            'Jobseeker_entry/Jobseeker_entry_notify', 
            null, 
            'Jobseeker_entry_notify'
        );

        $this->db->from('tbl_seeker_entries');
        $this->db->where('notified', 0);
        $this->db->where('seeker_id!=', null);
        $this->db->where('error_log', null);
        $this->db->limit(20);

        $result = $this->db->get()->result();
   
        foreach ($result as $seeker) {
            $this->Jobseeker_entry_notify->notify($seeker->email);
        }
    }
}
