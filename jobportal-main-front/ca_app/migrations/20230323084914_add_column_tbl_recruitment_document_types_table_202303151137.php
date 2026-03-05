<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_document_types_table_202303151137 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'option_type_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'allowed_files' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'max_size' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ]   
        ];

        $this->dbforge->add_column('tbl_recruitment_document_types', $fields);   

        $this->db->query("CREATE INDEX country_id ON tbl_recruitment_document_types (country_id)");
        $this->db->query("CREATE INDEX active ON tbl_recruitment_document_types (active)");

        $this->db->query('ALTER TABLE tbl_recruitment_document_types ADD CONSTRAINT FOREIGN KEY (option_type_id) REFERENCES tbl_recruitment_document_option_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }
    
    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_document_types', 'country_id');
        $this->dbforge->drop_colum('tbl_recruitment_document_types', 'active');
        $this->dbforge->drop_colum('tbl_recruitment_document_types', 'allowed_files');
        $this->dbforge->drop_colum('tbl_recruitment_document_types', 'max_size');
    }
}
