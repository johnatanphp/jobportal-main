<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202407311120 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'stage_group_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
        $this->db->query('ALTER TABLE tbl_staff_requests ADD CONSTRAINT FOREIGN KEY (stage_group_id) REFERENCES tbl_recruitment_stages_groups(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'stage_group_id');
    }
}
