<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_job_layouts_202405291250 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_job_layouts CHANGE code code VARCHAR(20);");
        $this->db->query("ALTER TABLE tbl_job_layouts DROP INDEX code;");
        $this->db->query("CREATE UNIQUE INDEX code ON tbl_job_layouts (code);");
    }

    public function down(){}
}
