<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_occupational_categories_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '120'
            ],
            'level' => [
                'type' => 'INT'
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ];
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_occupational_categories');

        //Insert data
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_occupational_categories', TRUE);
    }

    private function insert_data()
    {
        $insert[] = ['CARGOS DIRECTIVOS'	,1];
        $insert[] = ['JEFATURA'	,2];
        $insert[] = ['PROFESIONALES CIENTIFICOS E INTELECTUALES', 2];
        $insert[] = ['PROFESIONALES TECNICOS', 2];
        $insert[] = ['ASESORIA Y CONSULTORIA' 	,3];
        $insert[] = ['COORDINACIÓN', 3];
        $insert[] = ['ADMINISTRADOR DE PERSONAL', 3];
        $insert[] = ['SUPERVISION Y CONTROL' , 3];
        $insert[] = ['ANALISTAS', 3];
        $insert[] = ['CARGOS ADMINISTRATIVOS', 3];
        $insert[] = ['TRABAJADORES DE LOS SERVICIOS Y VENDEDORES DE COMERCIOS Y MERCADOS', 4];
        $insert[] = ['OPERADORES DE MAQUINARIA INDUSTRIAL, ENSAMBLADORES Y CONDUCTORES DE TRANSPORTE', 4];
        $insert[] = ['APOYO ADMINISTRATIVO', 4];
        $insert[] = ['APOYO COMERCIAL', 4];
        $insert[] = ['OCUPACIONES ELEMENTALES', 5];

        foreach ($insert as $row) {

            $data_row = [
                'name' => $row[0],
                'level' => $row[1],
                'active' => 1
            ];
            $this->db->insert('tbl_occupational_categories', $data_row);
        }
    }
}
