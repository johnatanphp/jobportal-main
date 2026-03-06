<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_tbl_recruitment_process_202508131736 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_recruitment_process DROP INDEX `PRIMARY`');
        $this->db->query('ALTER TABLE tbl_recruitment_process MODIFY COLUMN job_ID int NULL');
        $this->db->query("CREATE INDEX job_ID ON tbl_recruitment_process (job_ID)");
    }

    public function down(){}
}
