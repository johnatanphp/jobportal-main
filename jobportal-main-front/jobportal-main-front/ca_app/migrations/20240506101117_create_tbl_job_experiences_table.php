<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_experiences_table extends CI_Migration
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
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_job_experiences');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_experiences');
    }

    public function insert_data()
    {
        $records = [
            'Autoservicios',
            'Tiendas por departamento'
        ];

        foreach ($records as $exp_name) {
            $this->db->insert('tbl_job_experiences', [
                'name' => $exp_name
            ]);
        }
    }
}
//