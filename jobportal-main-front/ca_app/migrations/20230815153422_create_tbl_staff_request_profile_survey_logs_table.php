<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_profile_survey_logs_table extends CI_Migration
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
                'unsigned' => FALSE,
            ],
            'employer_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
            ],
            'date' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->create_table('tbl_staff_request_profile_survey_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_profile_survey_logs');
    }
}
