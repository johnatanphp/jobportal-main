<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_document_option_types extends CI_Migration
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
                'null' => false,
                'constraint' => '25',
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_document_option_types');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_document_option_types');
    }

    public function insert_data()
    {
        $this->db->insert('tbl_recruitment_document_option_types', [
            'id' => 1,
            'name' => 'Adjunto'
        ]);

        $this->db->insert('tbl_recruitment_document_option_types', [
            'id' => 2,
            'name' => 'Programado'
        ]);

        $this->db->insert('tbl_recruitment_document_option_types', [
            'id' => 3,
            'name' => 'Formulario'
        ]);
    }
}
