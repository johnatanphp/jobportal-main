<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_modules_table extends CI_Migration
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
            'keyword_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1,
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_modules');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_modules');
    }
}
