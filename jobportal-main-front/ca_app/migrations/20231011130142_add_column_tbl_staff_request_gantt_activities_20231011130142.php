<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_request_gantt_activities_20231011130142 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'type_id' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
            ],
        ];

        $this->dbforge->add_column('tbl_staff_request_gantt_activities', $fields); 

        $this->db->query("CREATE INDEX type_id ON tbl_staff_request_gantt_activities (type_id)");   
        
        $this->update_types();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_gantt_activities');
    }

    private function update_types() {
        $this->db->where('type_id', null);
			$this->db->update('tbl_staff_request_gantt_activities', [
				'type_id' => 1 
			]);
    }
}
