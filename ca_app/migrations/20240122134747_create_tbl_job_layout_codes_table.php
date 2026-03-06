<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_codes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'acronym' => [
                'type' => 'VARCHAR',
                'constraint' => 40,
                'unique' => TRUE
            ],
            'number_correlative' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
            'country_id' => [
                'type' => 'INT',
                'unique' => TRUE
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (country_id) REFERENCES tbl_countries(ID)');
        $this->dbforge->add_key('id', TRUE);        
        $this->dbforge->create_table('tbl_job_layout_codes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_codes');
    }
}
