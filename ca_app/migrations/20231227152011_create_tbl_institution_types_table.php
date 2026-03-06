<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_institution_types_table extends CI_Migration
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
                'constraint' => '180',
            ],
            'active' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_institution_types');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_institution_types');
    }

    private function insert_data()
    {
        $records = [
            ['4', 'INSTITUTO DE EDUCACION SUPERIOR TECNOLÓGICA'],
            ['5', 'INSTITUTO SUPERIOR PEDAGOGICO'],
            ['6', 'UNIVERSIDAD'],
            ['7', 'EDUCACION SUPERIOR DE FORMACION ARTISTICA'],
            ['8', 'ESCUELAS E INSTITUTOS DE EDUCACION SUPERIOR TECNOLÓGICOS'],
            ['9', 'NO ESPECIFICADO']            
        ];

        foreach ($records as $row ) {
            $this->db->insert('tbl_institution_types', [
                'id' => $row[0],
                'name' => $row[1]
            ]);
        }
    }
}
