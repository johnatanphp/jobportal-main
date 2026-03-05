<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_update_data_tbl_identity_document_types_202510271750 extends CI_Migration
{
    public function up()
    {
        $this->db->where_in('id', [
            '1',
            '4',
            '7',
            '12',
            '26'
        ]);
        $this->db->update('tbl_identity_document_types', [
            'country_id' => '56' //Peru
        ]);
    }

    public function down(){}
}
