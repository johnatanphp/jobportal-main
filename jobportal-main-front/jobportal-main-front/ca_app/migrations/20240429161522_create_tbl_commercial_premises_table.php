<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_commercial_premises_table extends CI_Migration
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
            'chain_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (chain_id) REFERENCES tbl_chains(id)');
        $this->dbforge->create_table('tbl_commercial_premises');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_commercial_premises');
    }
}
//