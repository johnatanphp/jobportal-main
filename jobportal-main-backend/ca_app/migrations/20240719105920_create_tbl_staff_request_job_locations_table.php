<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_job_locations_table extends CI_Migration
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
        $this->dbforge->add_key('department_id');
        $this->dbforge->add_key('province_id');
        $this->dbforge->add_key('district_id');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (country_id) REFERENCES tbl_countries(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->create_table('tbl_staff_request_job_locations');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_job_locations');
    }
}
