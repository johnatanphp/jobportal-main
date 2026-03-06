<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_sessions_202402021615 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_sessions CHANGE id id varchar(128) NOT NULL;");
    }

    public function down(){}
}
