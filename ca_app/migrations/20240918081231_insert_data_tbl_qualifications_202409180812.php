<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_qualifications_202409180812 extends CI_Migration
{
    public function up() {
        $data = [
            ['Superior', 'Estudios_2', 1, 56],
            ['Técnico', 'Estudios_2', 1, 56],
            ['Otros', 'Estudios_2', 1, 56],
        ];

        foreach ($data as $qualification_row) {
            $text = $qualification_row[0];
            $val = $qualification_row[1];
            $active = $qualification_row[2];
            $country_id = $qualification_row[3];

            $row = $this->db->get_where('tbl_qualifications', [
                'text' => $text,
                'val' => $val,
                'country_id' => $country_id
            ])->row();

            if (!$row) {
                $this->db->insert('tbl_qualifications', [
                    'text' => $text,
                    'val' => $val,
                    'active' => $active,
                    'country_id' => $country_id
                ]);
            }
        }
    }

    public function down() {
        $this->db->where('val', 'Estudios_2');
        $this->db->where('country_id', 56);
        $this->db->delete('tbl_qualifications');
    }
}
