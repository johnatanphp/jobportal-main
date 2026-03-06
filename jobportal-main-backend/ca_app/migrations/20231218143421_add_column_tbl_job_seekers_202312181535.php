<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_seekers_202312181535 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'way_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],    
            'address_number' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_job_seekers', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_seekers', 'way_id');
        $this->dbforge->drop_colum('tbl_job_seekers', 'address_number');
    }
}
