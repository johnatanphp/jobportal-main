<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202401151750 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'job_profile_ID'
            ]
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
        $this->db->query("CREATE INDEX job_layout_id ON tbl_staff_requests (job_layout_id)"); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_layout_id');
    }
}
