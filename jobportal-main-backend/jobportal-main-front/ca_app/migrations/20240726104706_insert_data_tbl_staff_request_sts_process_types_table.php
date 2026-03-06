<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_staff_request_sts_process_types_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => false
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_staff_request_sts_process_types');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_sts_process_types');
    }
}
