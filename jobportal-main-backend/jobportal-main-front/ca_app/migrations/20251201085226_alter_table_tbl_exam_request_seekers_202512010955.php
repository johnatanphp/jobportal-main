<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_exam_request_seekers_202512010955 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'medical_center_location_code' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => true,
                'after' => 'medical_center_location_id'
            ]
        ];

        $this->dbforge->add_column('tbl_exam_request_seekers', $fields);
    }

    public function down(){}
}
