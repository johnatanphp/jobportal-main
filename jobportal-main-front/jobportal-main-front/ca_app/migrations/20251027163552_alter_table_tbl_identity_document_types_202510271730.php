<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_identity_document_types_202510271730 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_identity_document_types', $fields);
        $this->db->query('ALTER TABLE tbl_identity_document_types ADD CONSTRAINT FOREIGN KEY (country_id) REFERENCES tbl_countries(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_identity_document_types', 'country_id');
    }
}
