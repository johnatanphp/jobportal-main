<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_staff_requests_202409021820 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_staff_requests MODIFY COLUMN cost_center varchar(36) NOT NULL;");
    }

    public function down(){}
}
