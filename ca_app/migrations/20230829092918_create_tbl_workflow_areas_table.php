<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_workflow_areas_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ],
            'cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('code');
        $this->dbforge->add_key('cia_code');
        $this->dbforge->add_key('company_id');
        $this->dbforge->add_key('active');
        
        $this->dbforge->create_table('tbl_workflow_areas');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_workflow_areas');
    }
}
