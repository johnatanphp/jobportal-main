<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_canceled_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'auto_increment' => TRUE
            ],
            'request_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => false
            ],
            'canceled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'canceled_by_employer_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (canceled_by_employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->create_table('tbl_staff_request_canceled');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_canceled');
    }
}
