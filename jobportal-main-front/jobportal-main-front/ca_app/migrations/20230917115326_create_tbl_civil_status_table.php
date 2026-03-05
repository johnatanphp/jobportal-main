<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_civil_status_table extends CI_Migration
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
        $this->dbforge->create_table('tbl_civil_status');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_civil_status');
    }

    private function insert_data()
    {
        $civil_status = [
            1 => 'Soltero', //single
            2 => 'Casado', //married
            3 => 'Divorciado', //divorced
            4 => 'Conviviente' //cohabiting
        ];
        
        foreach ($civil_status as $id => $name) {
            $this->db->insert('tbl_civil_status', [
                'id' => $id,
                'name' => $name
            ]);
        }
    }
}
