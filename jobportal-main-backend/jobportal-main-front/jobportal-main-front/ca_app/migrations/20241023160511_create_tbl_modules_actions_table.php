<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_modules_actions_table extends CI_Migration
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
            ],
            'module_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1,
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (module_id) REFERENCES tbl_modules(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_modules_actions');

        $this->db->query("ALTER TABLE tbl_modules_actions ADD UNIQUE module_id_keyword_id (module_id, keyword_id)");
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_modules_actions');
    }
}
