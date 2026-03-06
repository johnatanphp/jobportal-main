<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_assignments_table extends CI_Migration
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
                'null' => false
            ],
            'assignment_date' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'assigned_by_employer_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'reassigned_by_employer_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
            'reassignment_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'reassignment_reason' => [
                'type' => 'TEXT',
                'null' => false
            ], 
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->create_table('tbl_staff_request_assignments');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_assignments');
    }
}
