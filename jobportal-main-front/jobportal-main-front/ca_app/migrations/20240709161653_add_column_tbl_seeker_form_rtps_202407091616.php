<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_202407091616 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'pension_is_affiliate' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
                'after' => 'n_children',
            ],
            'pension_change_pension' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => TRUE,
                'after' => 'pension_is_affiliate',
            ],
            'pension_desired_pension_system' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'after' => 'pension_change_pension',
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'pension_is_affiliate');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'pension_change_pension');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'pension_desired_pension_system');
    }
}
