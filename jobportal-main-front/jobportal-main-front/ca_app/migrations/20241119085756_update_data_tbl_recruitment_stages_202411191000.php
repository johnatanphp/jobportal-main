<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_recruitment_stages_202411191000 extends CI_Migration
{
    public function up()
    {
        $this->db->where('id', 7);
        $this->db->update('tbl_recruitment_stages', [
            'name' => 'CONTRATACIÓN'
        ]);
    }

    public function down() {}
}
