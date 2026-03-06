<?php
require_once ("App_console.php");

class Migrate_job_layouts extends App_console  
{
    public function __construct()
    {
        parent::__construct(); 
    }

    public function run()
    {
        $this->load->library('App/Job_Layout/Job_layout_migration');
        $this->Job_layout_migration->migrate();
    }
}
