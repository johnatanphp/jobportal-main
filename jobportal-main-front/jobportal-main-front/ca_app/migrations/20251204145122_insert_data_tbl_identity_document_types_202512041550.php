<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_identity_document_types_202512041550 extends CI_Migration
{
    public function up()
    {
        $this->db->where('id', '27');
        $this->db->where('country_id', '49');
        $this->db->update('tbl_identity_document_types', [
            'hrmgo_code' => '01',
            'abbreviation' => 'CV'
        ]);
    }

    public function down(){}
}
