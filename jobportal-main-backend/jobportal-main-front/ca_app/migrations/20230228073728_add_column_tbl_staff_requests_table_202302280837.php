<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_table_202302280837 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'request_model_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'campaign' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'channel' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'service' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'health_card' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'type_contract' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'request_model_id');
        $this->dbforge->drop_colum('tbl_staff_requests', 'campaign');
        $this->dbforge->drop_colum('tbl_staff_requests', 'channel');
        $this->dbforge->drop_colum('tbl_staff_requests', 'service');
        $this->dbforge->drop_colum('tbl_staff_requests', 'delivery_date');
        $this->dbforge->drop_colum('tbl_staff_requests', 'health_card');
        $this->dbforge->drop_colum('tbl_staff_requests', 'type_contract');
    }
}
