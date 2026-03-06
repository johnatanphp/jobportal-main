<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_additional_info_202401051800 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'emergency_contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_additional_info', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_additional_info', 'emergency_contact_name');
    }
}
