<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_type_reasons_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'active' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_staff_request_type_reasons');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_type_reasons');
    }

    public function insert_data()
    {
        $records = [
            ['new', 'Nuevo puesto'],
            ['replacement', 'Reemplazo'],
            ['vacations', 'Vacaciones'],
            ['license', 'Licencia'],
            ['01', 'Nueva vacante'],
            ['03', 'Inicio de servicio'],
            ['04', 'Campaña'],
            ['08', 'Incremento de produccíon'],
            ['09', 'Proyecto'],
            ['10', 'Triangulación']
        ];

        foreach ($records as $row) {
            $this->db->insert('tbl_staff_request_type_reasons', [
                'id' => $row[0],
                'name' => $row[1]
            ]);
        }
    }
}
