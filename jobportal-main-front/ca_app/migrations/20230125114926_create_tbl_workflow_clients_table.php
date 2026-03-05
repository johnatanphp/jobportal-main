<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_workflow_clients_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '30'
            ],
            'name' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('company_id');
        $this->dbforge->add_key('code');
        $this->dbforge->add_key('active');

        $this->dbforge->create_table('tbl_workflow_clients');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_workflow_clients');
    }
}
