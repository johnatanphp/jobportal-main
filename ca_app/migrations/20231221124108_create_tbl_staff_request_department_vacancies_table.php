<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_department_vacancies_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'request_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => false
            ],
            'vacancies' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('request_id');
        
        $this->dbforge->create_table('tbl_staff_request_department_vacancies');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_department_vacancies');
    }
}
