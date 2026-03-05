<?php
require_once ("App_console.php");

class Exam_request_update_hrm extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->load->library(
            'Exam_request/Exam_request_update_lib', 
            null, 
            'Exam_request_update_lib'
        );

        $now = date('Y-m-d');

        $this->db->select([
            'ers.id AS ers_id'
        ]);
        $this->db->from('tbl_exam_request_seekers ers');
        $this->db->where('ers.notified_sso', 1);
        $this->db->where('ers.active', 1);
        $this->db->where('ers.scheduled_date>=', date("Y-m-d", strtotime($now . "- 5 days")));
        $this->db->where('ers.scheduled_date<=', date("Y-m-d", strtotime($now . "+ 5 days")));
        
        $exam_requests = $this->db->get()->result();

        $ers_ids = [];

        foreach ($exam_requests as $row) {
            $ers_ids[] = $row->ers_id;
        }

        $this->Exam_request_update_lib->run($ers_ids);
    }
}
