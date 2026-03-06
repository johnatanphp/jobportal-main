<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_campaigns_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
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
            ],
            'client_id' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],

        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('client_id');
        
        $this->dbforge->create_table('tbl_campaigns');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_campaigns');
    }

    private function insert_data()
    {
        $data = [
            [
                'name' => 'Campaña 1',
                'client_id' => 'C20100022142'
            ],
            [
                'name' => 'Campaña 2',
                'client_id' => 'C20100068487'
            ]
        ];

        foreach ($data as $row) {
            $this->db->insert('tbl_campaigns', [
                'name' => $row['name'],
                'client_id' => $row['client_id']
            ]);
        }
    }
}
//