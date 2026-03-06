<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_exam_request_seekers_table_202211231228 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'medical_center_name' => [
                'type' => 'text',
                'null' => false,
                'default' => '',
                'after' => 'medical_center_code'
            ],
            'medical_center_location_name' => [
                'type' => 'varchar',
                'constraint' => '80',
                'null' => false,
                'default' => '',
                'after' => 'medical_center_location_id'
            ],
            'notified_sso' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 0
            ],
        ];
        
        $this->dbforge->add_column('tbl_exam_request_seekers', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('medical_center_name', 'notified_sso');
        $this->dbforge->drop_colum('medical_center_location_name', 'notified_sso');
        $this->dbforge->drop_colum('tbl_exam_request_seekers', 'notified_sso');
    }
}
