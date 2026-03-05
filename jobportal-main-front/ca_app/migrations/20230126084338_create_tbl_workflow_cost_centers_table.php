<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_workflow_cost_centers_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
            ],
            'business_unit_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
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
        $this->dbforge->add_key('cia_code');
        $this->dbforge->add_key('business_unit_code');
        $this->dbforge->add_key('client_code');
        $this->dbforge->add_key('code');

        $this->dbforge->create_table('tbl_workflow_cost_centers');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_workflow_cost_centers');
    }
}
