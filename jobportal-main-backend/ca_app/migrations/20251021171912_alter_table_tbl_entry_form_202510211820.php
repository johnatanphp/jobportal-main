<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_entry_form_202510211820 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_entry_form DROP FOREIGN KEY tbl_entry_form_ibfk_4;");
        $this->db->query("ALTER TABLE tbl_entry_form DROP FOREIGN KEY tbl_entry_form_ibfk_5;");
        $this->dbforge->drop_column('tbl_entry_form', 'email');
        $this->dbforge->drop_column('tbl_entry_form', 'identity_document_type_id');
        $this->dbforge->drop_column('tbl_entry_form', 'identity_document_number');
        $this->dbforge->drop_column('tbl_entry_form', 'first_name');
        $this->dbforge->drop_column('tbl_entry_form', 'paternal_last_name');
        $this->dbforge->drop_column('tbl_entry_form', 'maternal_last_name');
        $this->dbforge->drop_column('tbl_entry_form', 'birthdate');
        $this->dbforge->drop_column('tbl_entry_form', 'gender_id');
        $this->dbforge->drop_column('tbl_entry_form', 'civil_status_id');
        $this->dbforge->drop_column('tbl_entry_form', 'mobile_phone');
        $this->dbforge->drop_column('tbl_entry_form', 'home_phone');
        $this->dbforge->drop_column('tbl_entry_form', 'address');
    }

    public function down(){}
}
