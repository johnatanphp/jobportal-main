<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_primary_key_tbl_recruitment_process_202508131845 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_recruitment_process MODIFY COLUMN id int unsigned auto_increment NOT NULL PRIMARY KEY");
    }

    public function down(){}
}
