<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_seekers_202312191800 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'domicile_interior' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_job_seekers', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_seekers', 'domicile_interior');
    }
}
