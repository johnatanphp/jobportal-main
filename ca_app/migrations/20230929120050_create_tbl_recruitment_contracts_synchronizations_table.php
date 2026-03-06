<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_contracts_synchronizations_table extends CI_Migration
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
                'type' => 'TINYINT',
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_contracts_synchronizations');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_contracts_synchronizations');
    }

    private function insert_data()
    {
        $list = [
            1 => 'Registrar trabajador',
            2 => 'Registrar Formación Académica',
            3 => 'Registrar Familiares',
            4 => 'Cargar foto',
            5 => 'Cargar CV',
            6 => 'Scanner Documento de identidad'
        ];
        
        foreach ($list as $id => $name) {
            $data = [
                'id' => $id,
                'name' => $name
            ];
            $this->db->insert('tbl_recruitment_contracts_synchronizations', $data);
        }
    }
}
