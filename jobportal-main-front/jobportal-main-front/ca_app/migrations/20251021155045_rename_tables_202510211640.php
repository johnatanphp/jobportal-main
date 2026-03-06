<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rename_tables_202510211640 extends CI_Migration
{
    public function up()
    {
        $this->db->query("RENAME TABLE tbl_entry_form_rightful_claimants_mx TO tbl_entry_form_mx_rightful_claimants;");
        $this->db->query("RENAME TABLE tbl_entry_form_extra_mx TO tbl_entry_form_mx;");
        $this->db->query("RENAME TABLE tbl_entry_form_people TO tbl_entry_form;");
    }

    public function down(){}
}
