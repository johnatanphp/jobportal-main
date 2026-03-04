<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_entry_form_rightful_claimants_mx extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'entry_form_id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => false
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
            'kinship_id' => [
                'type' => 'INT',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (entry_form_id) REFERENCES tbl_entry_form_people(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (kinship_id) REFERENCES tbl_kinship(id)');
        
        $this->dbforge->create_table('tbl_entry_form_rightful_claimants_mx');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_entry_form_rightful_claimants_mx');
    }
}
