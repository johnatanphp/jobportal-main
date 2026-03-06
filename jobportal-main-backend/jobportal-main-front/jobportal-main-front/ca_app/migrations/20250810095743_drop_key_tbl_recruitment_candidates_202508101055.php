<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Drop_key_tbl_recruitment_candidates_202508101055 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_recruitment_candidates DROP FOREIGN KEY tbl_recruitment_candidates_ibfk_2");
    }

    public function down(){}
}
