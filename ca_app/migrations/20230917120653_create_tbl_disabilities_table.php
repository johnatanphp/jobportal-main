<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_disabilities_table extends CI_Migration
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
        $this->dbforge->create_table('tbl_disabilities');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_disabilities');
    }

    private function insert_data()
    {    
        $disabilities = [
            1 => 'Física', //physical
            2 => 'Sensorial', //sensory
            3 => 'Mental', //mental
            4 => 'Intelectual' //intellectual
        ];
        
        foreach ($disabilities as $id => $name) {
            $this->db->insert('tbl_disabilities', [
                'id' => $id,
                'name' => $name
            ]);
        }
    }
}
