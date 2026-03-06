<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_recruitment_document_requests_202409101105 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_recruitment_document_requests MODIFY COLUMN created_by int NULL;");
    }

    public function down(){}
}
