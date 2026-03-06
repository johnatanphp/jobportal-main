<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_entry_form_extra_mx_table extends CI_Migration
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
        ]);
        $this->dbforge->add_key('entry_form_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (entry_form_id) REFERENCES tbl_entry_form_people(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (origin_country_id) REFERENCES tbl_countries(ID)');
        $this->dbforge->create_table('tbl_entry_form_extra_mx');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_entry_form_extra_mx');
    }
}
