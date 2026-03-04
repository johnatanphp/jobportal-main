<?php
require_once ("App_console.php");

class Rrs_trprocess extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->query("UPDATE tbl_recruitment_process SET id = job_ID where id = 0 limit 1000;");
        $this->db->query("UPDATE tbl_recruitment_candidates SET process_id = job_ID where (process_id = 0  or process_id is null) limit 5000;");
    }
}
