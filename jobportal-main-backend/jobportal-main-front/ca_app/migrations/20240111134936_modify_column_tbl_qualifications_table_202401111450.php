<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_qualifications_table_202401111450 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_qualifications MODIFY COLUMN ID INT auto_increment NOT NULL;");
    }

    public function down(){}
}
