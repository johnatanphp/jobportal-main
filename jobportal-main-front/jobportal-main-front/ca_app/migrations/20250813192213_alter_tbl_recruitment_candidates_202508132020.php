<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_tbl_recruitment_candidates_202508132020 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_recruitment_candidates ADD PRIMARY KEY (process_id, seeker_ID)");
    }

    public function down(){}
}
