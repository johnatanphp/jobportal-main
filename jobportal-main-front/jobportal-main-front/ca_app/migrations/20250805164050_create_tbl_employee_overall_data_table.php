<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employee_overall_data_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'unique' => true,
                'null' => false
            ],
            'overall_accumulated_working_time_days' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0
            ],
            'blacklisted' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0
            ],
            'blacklist_detail' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'life_time' => [    
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_employee_overall_data');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employee_overall_data');
    }
}
