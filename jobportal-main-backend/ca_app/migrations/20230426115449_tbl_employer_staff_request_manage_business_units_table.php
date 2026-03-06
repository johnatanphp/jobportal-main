<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_tbl_employer_staff_request_manage_business_units_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => false,
            ],
            'business_unit_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('business_unit_code');
        
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (user_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->create_table('tbl_employer_staff_request_manage_business_units');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employer_staff_request_manage_business_units');
    }
}
