<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_index_tbl_screening_202405231430 extends CI_Migration
{
    public function up()
    {
        $this->db->query("CREATE INDEX created_at ON tbl_screening (created_at)"); 
    }

    public function down(){}
}
