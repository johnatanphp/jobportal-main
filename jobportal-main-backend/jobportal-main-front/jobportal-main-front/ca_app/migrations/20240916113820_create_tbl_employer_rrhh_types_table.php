<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employer_rrhh_types_table extends CI_Migration
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
                'constraint' => '120',
                'null' => false
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_employer_rrhh_types');

        //Registrar datos
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employer_rrhh_types');
    }

    private function insert_data()
    {
        $data = [
            ['id' => 1, 'name' => 'Reclutador'],
            ['id' => 2, 'name' => 'Administrador de nómina'],
        ];

        foreach ($data as $row) {
            $this->db->insert('tbl_employer_rrhh_types', [
                'id' => $row['id'],
                'name' => $row['name']
            ]);
        }
    }
}
