<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_recruitment_stages_202511191135 extends CI_Migration
{
    public function up()
    {
        $stages = [
            '9' => 'Filtro telefónico (Llamar)',
            '10' => 'Programar entrevista',
            '11' => 'Entrevista con el reclutador',
            '12' => 'Programar entrevista con cliente',
            '13' => 'Entrevista con cliente',
            '18' => 'Screening',
            '14' => 'Programar Examen médico',
            '15' => 'Examen médico Ocupacional'
        ];

        foreach ($stages as $id => $name) {
            $this->db->where('id', $id);
            $this->db->update('tbl_recruitment_stages', [
                'name' => $name
            ]);
        }
    }

    public function down(){}
}
