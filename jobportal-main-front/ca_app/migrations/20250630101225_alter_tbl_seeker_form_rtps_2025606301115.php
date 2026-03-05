<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_tbl_seeker_form_rtps_2025606301115 extends CI_Migration
{
    public function up()
    {

        $fields = [
            'pension_is_affiliate' => [
                'name' => 'pension_affiliated',
                'type' => 'tinyint',
                'default' => '0',
            ],
            'pension_affiliation' => [
                'name' => 'pension_name',
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false
            ],
            'pension_name_afp' => [
                'name' => 'pension_type',
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => false
            ],
            'pension_change_pension' => [
                'name' => 'pension_change',
                'type' => 'tinyint',
                'null' => true
            ],
            'pension_desired_pension_system' => [
                'name' => 'pension_change_type',
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'pension_join_system_pension' => [
                'name' => 'pension_join',
                'type' => 'tinyint',
                'null' => true
            ]
        ];
        
        $this->dbforge->modify_column('tbl_seeker_form_rtps', $fields);
        
    }

    public function down(){}
}
