<?php
require_once ("App_console.php");

class Migration_workflow_overall2 extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->load->library(
            'Workflow_overall/Workflow_overall_update_lib', 
            null, 
            'Workflow_overall_update_lib'
        );
        $this->Workflow_overall_update_lib->exec();        
    }
}
