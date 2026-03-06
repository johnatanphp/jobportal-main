<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_entry_form_mx_202510211810 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false
            ],
            'identity_document_type_id' => [
                'type' => 'INT',
                'null' => true,
            ],
            'identity_document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '35',
                'null' => true
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
            'paternal_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
            'maternal_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
            'birthdate' => [
                'type' => 'DATE',
                'null' => true
            ],
            'gender_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'civil_status_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'mobile_phone' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => true
            ],
            'home_phone' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => true
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_entry_form_mx', $fields); 
        
        $this->db->query('ALTER TABLE tbl_entry_form_mx ADD CONSTRAINT FOREIGN KEY (gender_id) REFERENCES tbl_genders(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_entry_form_mx ADD CONSTRAINT FOREIGN KEY (civil_status_id) REFERENCES tbl_civil_status(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down(){}
}
