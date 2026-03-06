<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_process_202508101040 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false,
                'first' => TRUE,
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_process', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_process', 'id');
    }
}
