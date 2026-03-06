<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_email_applicant_phase_202503240915 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'send_email' => 'employer_email',
                'subject' => 'Seguimiento contratación para RRHH',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! estimado/a,</p>
                        <p>Se te asignó el proceso de reclutamiento para el puesto de "{{job_title}}" por favor ingresa a tu cuenta para hacer seguimiento/verificación de documentos a los candidatos para terminar su contratación.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Ingresar</a>
                    </div>'
            ]
        ];

        foreach ($data as $row) {
            $send_email = $row['send_email'];
            $subject = $row['subject'];
            $body = $row['body'];
        
            $this->db->insert('tbl_email_applicant_phase', [
                'send_email' => $send_email,
                'subject' => $subject,
                'body' => $body
            ]);
        }
    }

    public function down(){}
}
