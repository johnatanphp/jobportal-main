<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_exam_request_seekers_202502121425 extends CI_Migration
{
    public function up()
    {
        $add_update_status = [
            [
                'id' => 8,
                'color' => '#777777',
                'name' => 'Sin programar',
                'order' => 1
            ],
            [
                'id' => 1,
                'color' => '#337ab7',
                'name' => 'Programado RyS',
                'order' => 2
            ],
            [
                'id' => 2,
                'color' => '#e8b10d',
                'name' => 'Asignado',
                'order' => 3
            ],
            [
                'id' => 3,
                'color' => '#49b229',
                'name' => 'Programado SSO',
                'order' => 4
            ],
            [
                'id' => 9,
                'color' => '#2964b2',
                'name' => 'Confirmado',
                'order' => 5
            ],
            [
                'id' => 5,
                'color' => '#49b229',
                'name' => 'Asistio',
                'order' => 6
            ],
            [
                'id' => 6,
                'color' => '#e45555',
                'name' => 'No asistio',
                'order' => 7
            ],
            [
                'id' => 7,
                'color' => '#e45555',
                'name' => 'Cancelado',
                'order' => 8
            ]
        ];

        foreach ($add_update_status as $row) {

            $status_row = $this->db->get_where('tbl_exam_request_status', [
                'id' => $row['id']
            ])->row();

            if ($status_row) {
                $this->db->where('id', $status_row->id);
                $this->db->update('tbl_exam_request_status', [
                    'name' => $row['name'],
                    'color' => $row['color'],
                    'order' => $row['order']
                ]);
            } else {
                $this->db->insert('tbl_exam_request_status', [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'color' => $row['color'],
                    'order' => $row['order'],
                    'active' => '1'
                ]);
            }
        }
    }

    public function down(){}
}
