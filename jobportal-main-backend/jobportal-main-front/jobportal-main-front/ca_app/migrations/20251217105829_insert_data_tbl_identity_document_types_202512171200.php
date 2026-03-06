<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_identity_document_types_202512171200 extends CI_Migration
{
    public function up()
    {
        $dt_data = [
            [
                'id' => 30,
                'name' => 'Pasaporte',
                'key' => '',
                'abbreviation' => 'Pasaporte',
                'hrmgo_code' => '',
                'active' => 1,
                'country_id' => '18' // Ecuador
            ],
            [
                'id' => 31,
                'name' => 'Partida de nacimiento',
                'key' => 'PN',
                'abbreviation' => 'PN',
                'hrmgo_code' => '11',
                'active' => 0,
                'country_id' => '18' // Ecuador
            ],
            [
                'id' => 32,
                'name' => 'Partida de nacimiento',
                'key' => 'PN',
                'abbreviation' => 'PN',
                'hrmgo_code' => '11',
                'active' => 0,
                'country_id' => '49' // Mexico
            ]
        ];
  
        foreach ($dt_data as $row) {
            $doc = $this->db->get_where('tbl_identity_document_types', [
                'id' => $row['id']
            ])->row();

            if ($doc) {
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_identity_document_types', [
                    'hrmgo_code' => $row['hrmgo_code'],
                    'country_id' => $row['country_id']
                ]);
                continue;
            }

            $this->db->insert('tbl_identity_document_types', $row);
        }
    }

    public function down(){}
}
