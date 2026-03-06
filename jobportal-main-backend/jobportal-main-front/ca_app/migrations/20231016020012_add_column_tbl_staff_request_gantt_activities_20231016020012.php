<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_request_gantt_activities_20231016020012 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'other_activity' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '100'
            ],
        ];

        $this->dbforge->add_column('tbl_staff_request_gantt_activities', $fields); 
      
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_gantt_activities', 'other_activity');
    }
}
