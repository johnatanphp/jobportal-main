<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_job_layouts_202409061600 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_job_layouts MODIFY COLUMN study_grade_req int NULL;');
        $this->db->query('ALTER TABLE tbl_job_layouts MODIFY COLUMN study_grade_min int NULL;');
    }

    public function down() {}
}
