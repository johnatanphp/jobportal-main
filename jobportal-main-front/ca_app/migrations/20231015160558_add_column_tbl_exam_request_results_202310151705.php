<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_exam_request_results_202310151705 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'result_file' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ];
        $this->dbforge->add_column('tbl_exam_request_results', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_exam_request_results', 'result_file');
        $this->dbforge->drop_colum('tbl_exam_request_results', 'created_at');
    }
}
