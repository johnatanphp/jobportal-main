<?php
require_once ("App_console.php");

class Migration_staff_request_canceled extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'request.ID AS request_id',
            'request.reason_cancellation AS reason_cancellation',
            'request_canceled.request_id AS rc_id'
        ]);
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_staff_request_canceled request_canceled', 'request_canceled.request_id=request.ID', 'left');
        $this->db->where('sts_process', 'canceled');
        $this->db->having('rc_id IS NULL');
        $this->db->limit(200);
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
                
            $this->db->insert('tbl_staff_request_canceled', [
                'request_id' => $row->request_id,
                'reason' => trim((string)$row->reason_cancellation)
            ]);
        }   
    }
}
