<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_kinship_202401081540 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_kinship', [
            'id' => 6,
            'name' => 'Hijo mayor de edad hasta 24 años con estudios',
            'iplani_code' => '99'
        ]);
    }

    public function down(){}
}
