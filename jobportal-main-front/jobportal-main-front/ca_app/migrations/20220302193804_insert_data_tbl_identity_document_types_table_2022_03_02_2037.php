<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_identity_document_types_table_2022_03_02_2037 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_identity_document_types', [
            'id' => 12,
            'name' => 'PTP',
            'key' => 'PTP',
            'abbreviation' => 'PTP',
            'sunat_code' => '',
        ]);

        $this->db->insert('tbl_identity_document_types', [
            'id' => 13,
            'name' => 'CPP',
            'key' => 'cpp',
            'abbreviation' => 'CPP',
            'sunat_code' => '',
        ]);
    }

    public function down()
    {
        $this->db->where('id', 12);
        $this->db->delete('tbl_identity_document_types');

        $this->db->where('id', 13);
        $this->db->delete('tbl_identity_document_types');
    }
}
