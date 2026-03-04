<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_tbl_recruitment_process_202508181640 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'tray_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
                'before' => 'job_ID'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_process', $fields); 
    }

    public function down(){}
}
