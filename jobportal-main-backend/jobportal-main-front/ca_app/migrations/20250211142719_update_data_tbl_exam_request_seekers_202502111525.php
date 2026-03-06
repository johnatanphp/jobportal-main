<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_exam_request_seekers_202502111525 extends CI_Migration
{
    public function up()
    {
        $this->db->query('UPDATE tbl_exam_request_seekers SET `status`= "8" WHERE `status` = "0" OR `status` IS NULL;');
    }

    public function down(){}
}
