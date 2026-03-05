<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_exam_request_seekers_202602021945 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'medical_center_location_address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'medical_center_location_name'
            ]
        ];

        $this->dbforge->add_column('tbl_exam_request_seekers', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_exam_request_seekers', 'medical_center_location_address');
    }
}
