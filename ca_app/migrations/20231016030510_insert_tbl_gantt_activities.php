<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_tbl_gantt_activities extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_gantt_activities', [
            'activity_name' => 'OTROS',
           
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_gantt_activities');
    }
}
