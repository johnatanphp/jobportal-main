<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_recruitment_stages_202507311320 extends CI_Migration
{
    public function up()
    {
        $data_update_order = [
            'Validación de datos',
            'Llamar (Filtro telefónico)',
            'Programar entrevista con reclutador',
            'Entrevista reclutador',
            'Programar entrevista cliente',
            'Entrevista cliente',
            'Screening',
            'Programar evaluación médica',
            'Evaluación médica',
            'Aptos para contratar',
            'Enviados a contratación' 
        ];

        foreach ($data_update_order as $index => $stage) {

            $this->db->where('name', $stage);
            $this->db->where('stage_group_id', 2);
            $this->db->update('tbl_recruitment_stages', [
                'order' => $index + 1
            ]);
        }
    }

    public function down(){}
}
