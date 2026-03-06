<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Drop_key_tbl_recruitment_candidates_202508131920 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_recruitment_candidates DROP INDEX `PRIMARY`');
        $this->db->query('ALTER TABLE tbl_recruitment_candidates MODIFY COLUMN process_id int unsigned NOT NULL FIRST');
        $this->db->query("CREATE INDEX seeker_ID ON tbl_recruitment_candidates (seeker_ID)");
    }

    public function down(){}
}
