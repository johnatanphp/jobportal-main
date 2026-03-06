<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_mof_disability_themes_table extends CI_Migration
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
                'default' => 1
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_mof_disability_themes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_mof_disability_themes');
    }
}
