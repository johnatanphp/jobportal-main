<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employer_permission_job_charges_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'charge_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'NULL' => false
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'NULL' => false
            ],
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (charge_id) REFERENCES tbl_job_charges(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (user_id) REFERENCES tbl_employers(ID)');
        
        $this->dbforge->create_table('tbl_employer_permission_job_charges');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employer_permission_job_charges');
    }
}
