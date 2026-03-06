<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_contract_document_groups_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => false
            ],
            'description' => [
                'type' => 'TEXT',
                'default' => '',
                'null' => false 
            ],
            'active' => [
                'type' => 'tinyint',
                'constraint' => 1,
                'default' => 0,
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_contract_document_groups');

        //Registrar data
        $this->insert_data();
    }

    public function insert_data()
    {
        $this->db->insert('tbl_contract_document_groups', [
            'id' => 1,
            'name' => 'Ficha de ingreso',
            'description' => 'Grupo de homologación que permite la vinculación a los documentos de contratación (Fichas/Planillas) de Ingreso que pertenecen a diferentes países. Para uso interno y exclusivo de las empresas Overall',
            'active' => 1
        ]);

    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_contract_document_groups');
    }
}
