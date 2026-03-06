<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Drop_column_tbl_exam_request_results_20231023082 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_exam_request_results DROP COLUMN exam_type_id, DROP COLUMN document_type, DROP COLUMN result_file_id");        
    }

    public function down(){}
}
