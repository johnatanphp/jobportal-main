<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_request_assigned_employers_202311171020 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_staff_request_assigned_employers CHANGE `__id__` id int(10) unsigned auto_increment NOT NULL");
    }

    public function down(){}
}
