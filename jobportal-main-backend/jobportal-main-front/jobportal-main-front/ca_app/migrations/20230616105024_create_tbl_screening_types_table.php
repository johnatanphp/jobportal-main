<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_screening_types_table extends CI_Migration
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
                'constraint' => '100',
            ],
            'active' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('active');
        $this->dbforge->create_table('tbl_screening_types');

        $this->inser_data();
    }

    public function inser_data()
    {
        $types = [
            'Básico',
            'Integral'
        ];

        foreach ($types as $types_key => $row) {

            $data = [
                'id'  => ($types_key + 1),
                'name' => $row,
                'active' => 1
            ];
            $this->db->insert('tbl_screening_types', $data);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_screening_types');
    }
}
