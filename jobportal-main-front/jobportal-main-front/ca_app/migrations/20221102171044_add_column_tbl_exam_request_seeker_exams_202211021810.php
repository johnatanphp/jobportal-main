<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_exam_request_seeker_exams_202211021810 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'job_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'seeker_id' => [
                'type' => 'INT',
                'null' => false
            ]
        ];
        
        $this->dbforge->add_field("id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        $this->dbforge->add_column('tbl_exam_request_seeker_exams', $fields);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('seeker_id');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'id');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'job_id');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'seeker_id');
    }
}
