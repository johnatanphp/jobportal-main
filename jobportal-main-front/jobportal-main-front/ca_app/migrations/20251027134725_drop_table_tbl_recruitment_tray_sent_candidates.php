<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Drop_table_tbl_recruitment_tray_sent_candidates extends CI_Migration
{
    public function up()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_sent_candidates');
    }

    public function down(){}
}
