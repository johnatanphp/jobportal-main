<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_ca_hiring_type_reasons_202405281430 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_ca_hiring_type_reasons CHANGE jp_code portal_code VARCHAR(20);");
    }

    public function down(){}
}
