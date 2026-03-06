<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_process_202511171640 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'zone_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'commercial_premise_id'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_process', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_process', 'zone_id');
    }
}
