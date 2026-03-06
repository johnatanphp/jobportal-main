<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_tbl_exam_request_seekers_202502111625 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_exam_request_seekers MODIFY COLUMN `status` int unsigned DEFAULT 8 NOT NULL;');
    }

    public function down(){}
}
