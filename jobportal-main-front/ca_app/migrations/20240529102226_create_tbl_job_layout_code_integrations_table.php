<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_code_integrations_table extends CI_Migration
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
                'constraint' => 20,
                'unique' => TRUE
            ],
            'number_correlative' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);        
        $this->dbforge->create_table('tbl_job_layout_code_integrations');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_code_integrations');
    }

    private function insert_data()
    {
        $this->db->insert('tbl_job_layout_code_integrations', [
            'acronym' => 'P',
            'number_correlative' => 1
        ]);
    }
}
