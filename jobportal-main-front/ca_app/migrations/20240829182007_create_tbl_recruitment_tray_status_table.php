<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_tray_status_table extends CI_Migration
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
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_tray_status');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_status');
    }

    private function insert_data()
    {
        $status_data = [
            [
                'id' => 1,
                'name' => 'EN PROCESO'
            ],
            [
                'id' => 2,
                'name' => 'ENVIADO'
            ],
            [
                'id' => 3,
                'name' => 'CONTRATADO'
            ],
        ];

        foreach ($status_data as $row) {
            $this->db->insert('tbl_recruitment_tray_status', [
                'id' => $row['id'],
                'name' => $row['name'],
                'active' => 1
            ]);
        }
    }
}
