<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_recruitment_stages_202507311245 extends CI_Migration
{
    public function up()
    {
        $data_update = [
            [
                'id' => '9',
                'name' => 'Llamar (Filtro telefónico)',
            ],
            [
                'id' => '10',
                'name' => 'Programar entrevista con reclutador',
            ],
            [
                'id' => '11',
                'name' => 'Entrevista reclutador',
            ],
            [
                'id' => '12',
                'name' => 'Programar entrevista cliente',
            ],
            [
                'id' => '18',
                'name' => 'Screening'
            ],
        ];

        foreach ($data_update as $stages) {

            $id = $stages['id'];

            $stage_row  = $this->db->get_where('tbl_recruitment_stages', [
                'id' => $id
            ])->row();

            $name = $stages['name'];

            if ($stage_row) {
                $this->db->where('id', $id);
                $this->db->update('tbl_recruitment_stages', [
                    'name' => $name
                ]);
            } else {
                $this->db->insert('tbl_recruitment_stages', [
                    'id' => $id,
                    'name' => $name,
                    'stage_category_id' => 2,
                    'stage_group_id' => 2
                ]);
            }
        }
    }

    public function down(){}
}
