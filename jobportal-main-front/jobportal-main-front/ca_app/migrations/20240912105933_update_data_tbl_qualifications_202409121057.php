<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_qualifications_202409121057 extends CI_Migration
{
    public function up()
    {
        $this->db->query("UPDATE tbl_qualifications SET country_id = 56 WHERE val = 'Estudios'");
    }

    public function down()
    {
        $this->db->query("UPDATE tbl_qualifications SET country_id = NULL WHERE val = 'Estudios'");
    }
}
