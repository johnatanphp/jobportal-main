<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_job_processes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'country_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'department_id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'default' => ''
            ],
            'province_id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'default' => ''
            ],
            'district_id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'default' => ''
            ],
            'commercial_premise_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'vacancies' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'request_id' => [
                'type' => 'INT',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (commercial_premise_id) REFERENCES tbl_commercial_premises(id)');
        $this->dbforge->create_table('tbl_staff_request_job_processes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_job_processes');
    }
}
