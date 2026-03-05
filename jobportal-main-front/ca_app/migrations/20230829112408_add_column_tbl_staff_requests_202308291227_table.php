<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202308291227_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'mof_ID' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => false
            ],
            'job_profile_ID' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => false
            ],
            'charge_ID' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => false
            ],
            'industry_ID' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => false,
            ],
            'wf_area_code' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '25',
            ]
        ];
      
        $this->dbforge->add_column('tbl_staff_requests', $fields); 
        
        $this->db->query("CREATE INDEX mof_ID ON tbl_staff_requests (mof_ID)");   
        $this->db->query("CREATE INDEX job_profile_ID ON tbl_staff_requests (job_profile_ID)");   
        $this->db->query("CREATE INDEX charge_ID ON tbl_staff_requests (charge_ID)");   
        $this->db->query("CREATE INDEX industry_ID ON tbl_staff_requests (industry_ID)");   
        $this->db->query("CREATE INDEX wf_area_code ON tbl_staff_requests (wf_area_code)");   
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'mof_ID');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_profile_ID');
        $this->dbforge->drop_colum('tbl_staff_requests', 'charge_ID');
        $this->dbforge->drop_colum('tbl_staff_requests', 'industry_ID');
        $this->dbforge->drop_colum('tbl_staff_requests', 'wf_area_code');
    }
}
