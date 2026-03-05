<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_genders_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_genders');

        $this->insert_data();
        
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_genders');
    }

    private function insert_data()
    {    
        $genders = [
            1 => 'Hombre', //male
            2 => 'Mujer', //female
        ];
        
        foreach ($genders as $id => $name) {
            $this->db->insert('tbl_genders', [
                'id' => $id,
                'name' => $name
            ]);
        }
    }
}
