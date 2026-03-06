<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_202407101317 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'degree_obtained_type' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => '',
                'null' => true,
                'after' => 'degree_obtained',
            ],
        ];

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'degree_obtained_type');
    }
}
