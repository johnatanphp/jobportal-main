<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_tray_screening_batches extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false
            ],
            'no_cia' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],
            'type_expense' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'cost_center' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],
            'cost_center_client' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => false
            ],
            'eecc_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
            ],
            'process_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'seeker_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
            ],
            'status_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'screening_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('process_id');
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (created_by) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (screening_id) REFERENCES tbl_screening(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (type_id) REFERENCES tbl_screening_types(id)');
        $this->dbforge->create_table('tbl_recruitment_tray_screening_batches');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_screening_batches');
    }
}



