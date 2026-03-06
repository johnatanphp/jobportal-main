<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_exam_request_seekers_20250219940 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'notify_recruiter' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
            ]
        ];

        $this->dbforge->add_column('tbl_exam_request_seekers', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_exam_request_seekers', 'notify_recruiter');
    }
}
