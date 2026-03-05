<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_column_tbl_workflow_consultants_202407181445 extends CI_Migration
{
    public function up()
    {
        $this->db->query("UPDATE tbl_workflow_consultants SET ruc = '20202020202'");
    }

    public function down()
    {
        $this->db->query("UPDATE tbl_workflow_consultants SET ruc = NULL");
    }
}
