<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_request_gantt_activities_202402151210 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'gantt_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true,
            ]
        ];

        $this->dbforge->add_column('tbl_staff_request_gantt_activities', $fields); 
        $this->db->query('ALTER TABLE tbl_staff_request_gantt_activities ADD CONSTRAINT FOREIGN KEY (gantt_id) REFERENCES tbl_staff_request_gantt(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_request_gantt_activities', 'gantt_id');
    }
}
