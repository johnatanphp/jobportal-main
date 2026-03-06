<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_column_tbl_exam_request_results_202309111212 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_exam_request_results DROP COLUMN `type`, DROP COLUMN loaded");   
    }

    public function down(){}
}
