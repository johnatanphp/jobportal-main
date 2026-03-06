<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_risk_criteria_table extends CI_Migration
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
                'unsigned' => true,
                'default' => 1
            ],
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('country_id');

        $this->dbforge->create_table('tbl_risk_criteria');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_risk_criteria');
    }
}
