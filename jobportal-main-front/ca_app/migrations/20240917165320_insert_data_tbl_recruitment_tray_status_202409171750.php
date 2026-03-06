<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_recruitment_tray_status_202409171750 extends CI_Migration
{
    public function up()
    {
        $this->insert_data();
    }

    public function down(){}

    private function insert_data()
    {
        $status_data = [
            [
                'id' => 4,
                'name' => 'PROCESANDO'
            ],
            [
                'id' => 5,
                'name' => 'FALLIDO'
            ],
        ];

        foreach ($status_data as $row) {

            $row_status = $this->db->get_where('tbl_recruitment_tray_status', [
                'id' => $row['id']
            ])->row();

            if (!$row_status) {
                $this->db->insert('tbl_recruitment_tray_status', [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'active' => 1
                ]);
            } else {
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_recruitment_tray_status', [
                    'name' => $row['name'],
                    'active' => 1
                ]);
            }
        }
    }
}
