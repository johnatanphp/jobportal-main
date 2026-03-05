<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_permission_clients_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'consultant_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('consultant_code');
        $this->dbforge->add_key('client_code');
        $this->dbforge->create_table('tbl_job_layout_permission_clients');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_permission_clients');
    }
}
