<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_exam_request_status_table extends CI_Migration
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
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false,
            ],
            'order' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 1
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 1,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_exam_request_status');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_exam_request_status');
    }

    private function insert_data()
    {
        $data_status = [
            ['id' => '1', 'name' => 'Programada RyS'],
            ['id' => '2', 'name' => 'Asignada'],
            ['id' => '3', 'name' => 'Enviada'],
            ['id' => '4', 'name' => 'Reprogramada SSO', 'active' => 0],
            ['id' => '5', 'name' => 'Realizada'],
            ['id' => '6', 'name' => 'No asistido'],
            ['id' => '7', 'name' => 'Cancelado'],
            ['id' => '8', 'name' => 'Sin programar'],
        ];

        foreach ($data_status as $row) {
            $this->db->insert('tbl_exam_request_status', [
                'id' => $row['id'],
                'name' => $row['name'],
                'color' => $row['color'] ?? '',
                'active' => $row['active'] ?? '1'
            ]);
        }
    }
}
