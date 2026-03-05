<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_entry_form_people_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'BIGINT',
                'null' => false,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'form_country_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'seeker_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false,
            ],
            'has_digital_signature' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
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
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('has_digital_signature');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (form_country_id) REFERENCES tbl_countries(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (seeker_id) REFERENCES tbl_job_seekers(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (process_id) REFERENCES tbl_recruitment_process(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (gender_id) REFERENCES tbl_genders(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (civil_status_id) REFERENCES tbl_civil_status(id)');

        $this->dbforge->create_table('tbl_entry_form_people');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_entry_form_people');
    }
}
