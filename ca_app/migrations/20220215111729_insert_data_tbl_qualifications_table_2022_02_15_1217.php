<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_qualifications_table_2022_02_15_1217 extends CI_Migration
{
    public function up()
    {
        $insert[] = ['Estudios_t3', 'ESTUDIO TÉCNICO O UNIVERSITARIO CONCLUIDO'];
        $insert[] = ['Estudios_t3', 'ESTUDIANTE TÉCNICO O UNIVERSITARIO'];
        $insert[] = ['Estudios_t3', 'NO NECESITA EDUCACIÓN O SECUNDARIA COMPLETA'];
        $insert[] = ['Estudios_t3', 'CON ESTUDIOS COMPLEMENTARIOS (CURSOS/DIPLOMADOS/ ESPECIALIZACIONES)'];
        $insert[] = ['Estudios_t3', 'MAESTRÍAS/MBA'];
        
        foreach ($insert as $row) {

            $qualification = $this->db->get_where('tbl_qualifications', [
                'val' => trim($row[0]),
                'text' => trim($row[1])
            ])->row();

            if ($qualification) {
                continue;
            }

			$this->db->insert('tbl_qualifications', [
				'val' => trim($row[0]),
                'text' => trim($row[1])
			]);
        }  
    }

    public function down(){}
}
