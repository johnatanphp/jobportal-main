<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_qualifications_table_202302210914 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'valorization_score' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
            'valorization_grade' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_qualifications', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_qualifications', 'valorization_score');
        $this->dbforge->drop_colum('tbl_qualifications', 'valorization_grade');
    }
}
