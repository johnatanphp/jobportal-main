<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_exam_request_results_202602021945 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'observations' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'result_file'
            ]
        ];

        $this->dbforge->add_column('tbl_exam_request_results', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_exam_request_results', 'observations');
    }
}
