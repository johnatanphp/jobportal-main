<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_column_tbl_recruitment_candidates_202309190929 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_recruitment_candidates DROP COLUMN `hiring_error_log`, DROP COLUMN `no_cia`');
    }

    public function down(){}
}
