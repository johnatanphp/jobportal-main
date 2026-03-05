<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_identity_document_types_202510271820 extends CI_Migration
{
    public function up()
    {
        $dt_mx = [
            [
                'name' => 'Credencial',
                'key' => '',
                'abbreviation' => 'Credencial',
                'country_id' => '49' //Mexico
            ],
            [
                'name' => 'Pasaporte',
                'key' => '',
                'abbreviation' => 'Pasaporte',
                'country_id' => '49' //Mexico
            ]
        ];

        foreach ($dt_mx as $row) {
            $this->db->insert('tbl_identity_document_types', $row);
        }
    }

    public function down(){}
}
