<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_index_mof_code_202302211838 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_mofs DROP INDEX code");
    }

    public function down(){}
}
