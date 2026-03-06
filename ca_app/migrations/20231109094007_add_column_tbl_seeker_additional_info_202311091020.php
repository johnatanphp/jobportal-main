<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_additional_info_202311091020 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'emergency_contact_kinship' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => true
            ],
            'emergency_contact_mobile' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_seeker_additional_info', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_additional_info', 'emergency_contact_kinship');
        $this->dbforge->drop_colum('tbl_seeker_additional_info', 'emergency_contact_mobile');
    }
}
