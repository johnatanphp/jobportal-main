<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_factor_themes_table extends CI_Migration
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
                'type' => 'TINYINT',
                'default' => 1,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_factor_themes');

        //Insert data
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_factor_themes');
    }

    private function insert_data()
    {
        $data = [
            'CALIFICACIONES O COMPETENCIAS',
            'ESFUERZOS',
            'RESPONSABILIDADES',
            'CONDICIONES DE TRABAJO'
        ];

        foreach ($data as $row) {

            $row_theme = $this->db->get_where('tbl_factor_themes', [
                'name' => $row
            ])->row();

            if ($row_theme) {
                continue;
            }

            $insert = [
                'name' => $row,
                'active' => 1
            ];
            $this->db->insert('tbl_factor_themes', $insert);
        }
    }
}
