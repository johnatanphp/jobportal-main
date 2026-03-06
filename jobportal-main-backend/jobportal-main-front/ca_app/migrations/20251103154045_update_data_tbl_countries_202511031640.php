<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_countries_202511031640 extends CI_Migration
{
    public function up()
    {
        $this->db->where('ID', 49);
        $this->db->update('tbl_countries', [
            'currency_code' => 'MXN'
        ]);
    }

    public function down(){}
}
