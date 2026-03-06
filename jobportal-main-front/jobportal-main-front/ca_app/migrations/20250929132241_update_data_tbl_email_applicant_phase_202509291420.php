<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_email_applicant_phase_202509291420 extends CI_Migration
{
    public function up()
    {
        $this->db->where('id', 6);
        $this->db->update('tbl_email_applicant_phase', [
            'body' => '
                <div style="padding: 10px 10px;">
                    <p>Felicitaciones {{candidate_name}},</p>
                    <p>estás pasando a la etapa de {{stage_name}} para el puesto de {{job_title}}.</p>
                    <br>
                    <p>Atentamente,</p>
                    <p>El equipo de selección</p>
                </div>
                <div class="wrapper-paragraph">
                    <a href="{{url_link}}">Ver detalle</a>
                </div>'
        ]);
    }

    public function down(){}
}
