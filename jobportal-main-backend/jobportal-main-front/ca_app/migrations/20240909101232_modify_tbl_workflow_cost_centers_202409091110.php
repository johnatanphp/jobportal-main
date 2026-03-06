<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_tbl_workflow_cost_centers_202409091110 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_workflow_cost_centers MODIFY COLUMN code varchar(36) NOT NULL;");
    }

    public function down(){}
}
