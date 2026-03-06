<?php
require_once ("App_console.php");

class Cron_test extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->insert('tbl_cron_test', [
            'description' => 'TEST 1 - ' . date('Y-m-d H:i')
        ]);    
    }
}