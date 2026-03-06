<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_exam_request_employers extends CI_Migration
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
                'null' => false
            ],
            'employer_id' => [
                'type' => 'INT',
                'null' => false
            ],
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('employer_id');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->create_table('tbl_staff_request_exam_request_employers');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_exam_request_employers');
    }
}
