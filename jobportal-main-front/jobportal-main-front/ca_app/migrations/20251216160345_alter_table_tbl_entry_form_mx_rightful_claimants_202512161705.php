<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_entry_form_mx_rightful_claimants_202512161705 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'second_name' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => false,
                'after' => 'first_name'
            ],
            'identity_document_type_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],
            'identity_document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '35',
                'null' => true
            ],
            'gender_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'birthdate' => [
                'type' => 'DATE',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_entry_form_mx_rightful_claimants', $fields);
        $this->db->query('ALTER TABLE tbl_entry_form_mx_rightful_claimants ADD CONSTRAINT FOREIGN KEY (identity_document_type_id) REFERENCES tbl_identity_document_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_entry_form_mx_rightful_claimants ADD CONSTRAINT FOREIGN KEY (gender_id) REFERENCES tbl_genders(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_entry_form_mx_rightful_claimants', 'second_name');
        $this->dbforge->drop_colum('tbl_entry_form_mx_rightful_claimants', 'identity_document_type_id');
        $this->dbforge->drop_colum('tbl_entry_form_mx_rightful_claimants', 'identity_document_number');
        $this->dbforge->drop_colum('tbl_entry_form_mx_rightful_claimants', 'gender_id');
        $this->dbforge->drop_colum('tbl_entry_form_mx_rightful_claimants', 'birthdate');
    }
}
