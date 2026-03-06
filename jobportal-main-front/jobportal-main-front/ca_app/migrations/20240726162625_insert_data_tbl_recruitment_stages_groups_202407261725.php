<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_recruitment_stages_groups_202407261725 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_recruitment_stages_groups', [
            'id' => 1,
            'name' => 'Etapas por defecto'
        ]);

        $this->db->where_in('id', [0,1,2,3,4,5,6,7]);
        $this->db->update('tbl_recruitment_stages', [
            'stage_group_id' => 1
        ]);
    }

    public function down(){}
}
