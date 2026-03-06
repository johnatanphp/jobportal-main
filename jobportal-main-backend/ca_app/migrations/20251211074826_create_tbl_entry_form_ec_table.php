<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_entry_form_ec_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'entry_form_id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'work_city' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => true,
            ],
            'social_security_number' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => true
            ],
            'origin_country_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'visa_type' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => true
            ],
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
            'second_name' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => false,
                'after' => 'first_name'
            ],
            'third_name' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => false,
                'after' => 'second_name'
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
        ]);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (entry_form_id) REFERENCES tbl_entry_form(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (origin_country_id) REFERENCES tbl_countries(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (gender_id) REFERENCES tbl_genders(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (civil_status_id) REFERENCES tbl_civil_status(id)');
        $this->dbforge->create_table('tbl_entry_form_ec');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_entry_form_ec');
    }
}
