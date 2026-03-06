<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_factor_types_table extends CI_Migration
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
            'automatic' => [
                'type' => 'TINYINT',
                'default' => 0,
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_factor_types');

        //Insert data
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_factor_types');
    }

    public function insert_data()
    {
        $data = [
            ['EDUCACIÓN Y FORMACIÓN ACADÉMICA', 1],
            ['EXPERIENCIA LABORAL', 1],
            ['HABILIDADES', 1],
            ['MENTALES', 0],
            ['FÍSICOS', 0],
            ['RESPONSABILIDADES', 0],
            ['ENTORNO FÍSICO (INSTALACIONES / RUIDO / TEMPERATURA / RIESGO)', 0]
        ];

        foreach ($data as $row) {

            $row_types = $this->db->get_where('tbl_factor_types', [
                'name' => $row[0]
            ])->row();

            if ($row_types) {
                continue;
            }

            $insert = [
                'name' => $row[0],
                'automatic' => $row[1],
                'active' => 1
            ];
            $this->db->insert('tbl_factor_types', $insert);
        }
    }
}
