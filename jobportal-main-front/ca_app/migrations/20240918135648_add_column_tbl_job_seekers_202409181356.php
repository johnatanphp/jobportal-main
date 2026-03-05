<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_seekers_202409181356 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'its_reniec' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => true,
                'default' => 0,
            ],
        ];

        $this->dbforge->add_column('tbl_job_seekers', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_seekers', 'its_reniec');
    }
}
