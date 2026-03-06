<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_tbl_employers_202409241555 extends CI_Migration
{
    public function up()
    {
        $this->db->where('company_ID', 1);
        $this->db->update('tbl_employers', [
            'rrhh_type_id' => 1
        ]);
    }

    public function down()
    {
        $this->db->where('company_ID', 1);
        $this->db->update('tbl_employers', [
            'rrhh_type_id' => null
        ]);
    }
}
