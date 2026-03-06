<?php
require_once ("App_console.php");

class Migration_sap_workflow_overall extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->load->library(
            'WS_sap/WS_sap_workflow_overall_update_external_lib', 
            null, 
            'WS_sap_workflow_overall_update_external_lib'
        );
        $this->WS_sap_workflow_overall_update_external_lib->exec();        
    }
}
