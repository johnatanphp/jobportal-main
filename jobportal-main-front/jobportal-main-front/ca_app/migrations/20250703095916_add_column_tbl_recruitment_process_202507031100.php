<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_process_202507031100 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'closed_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'sts'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_process', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_process', 'closed_at');
    }
}
