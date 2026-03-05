<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_seeker_skills_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'skill_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'request_id' => [
                'type' => 'INT',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (skill_id) REFERENCES tbl_skills(ID)');
        $this->dbforge->create_table('tbl_staff_request_seeker_skills');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_seeker_skills');
    }
}
