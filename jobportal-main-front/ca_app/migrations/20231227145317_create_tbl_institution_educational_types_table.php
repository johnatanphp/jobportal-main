<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_institution_educational_types_table extends CI_Migration
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
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_institution_educational_types');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_institution_educational_types');
    }

    private function insert_data()
    {
        $this->db->insert('tbl_institution_educational_types', [
            'id' => 1,
            'name' => 'Publica',
            'active' => 1
        ]);

        $this->db->insert('tbl_institution_educational_types', [
            'id' => 2,
            'name' => 'Privada',
            'active' => 1
        ]);
    }
}
