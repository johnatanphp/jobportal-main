<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employee_categories_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'active' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_employee_categories');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employee_categories');
    }

    public function insert_data()
    {
        $categories = [
            'SIN CATEGORIA',
            'EJECUTIVO',
            'OBRERO',
            'EMPLEADO',
            'OFICIAL',
            'OPERARIO',
            'PEON',
            'GERENCIA',
            'JEFE',
            'FUNCIONARIO',
            'PROFESIONAL',
            'TECNICO',
            'AUXILIAR',
            'PRACTICANTE',
            'FUNCIONARIO PÚBLICO',
            'DIRECTIVO PÚBLICO'
        ];

        foreach ($categories as $row) {
            $this->db->insert('tbl_employee_categories', [
                'name' => $row
            ]);
        }
    }
}
