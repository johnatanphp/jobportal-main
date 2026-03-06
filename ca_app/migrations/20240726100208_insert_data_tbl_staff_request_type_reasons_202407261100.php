<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_staff_request_type_reasons_202407261100 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_staff_request_type_reasons', [
            'id' => '15',
            'name' => 'Normal',
            'active' => 1
        ]);

        $this->db->insert('tbl_staff_request_type_reasons', [
            'id' => '16',
            'name' => 'Reposición',
            'active' => 1
        ]);
    }

    public function down()
    {
        $this->db->where_in('id', ['15', '16']);
        $this->db->delete('tbl_staff_request_type_reasons');
        
    }
}
