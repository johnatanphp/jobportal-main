<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_form_rtps_rightful_claimants_202408091410 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_form_rtps_rightful_claimants MODIFY COLUMN gender int unsigned NULL;');
    }

    public function down(){}
}
