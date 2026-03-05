<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_email_applicant_phase_202503121310 extends CI_Migration
{
    public function up()
    {
        $this->db->where('id', 1);
        $this->db->update('tbl_email_applicant_phase', [
            'send_email' => 'recruitment_email',
            'body' => '<div style="padding: 10px 10px;">
                            <p>¡Hola! {{recruitment_name}},</p>
                            <p>Tu solicitud {{request_id}}-{{job_title}} fue enviada exitosamente.</p>
                            <br>
                            <p>Atentamente,</p>
                            <p>El equipo de selección</p>
                        </div>'
        ]);

        $this->db->where('id', 2);
        $this->db->update('tbl_email_applicant_phase', [
            'send_email' => 'employer_email',
            'subject' => 'Asignación Solicitud',
            'body' => '<div style="padding: 10px 10px;">
                            <p>¡Hola! {{employer_name}},</p>
                            <p>Se te asignó el proceso de: <b>{{job_title}}</b></p>
                            <br>
                            <p>Atentamente,</p>
                            <p>El equipo de selección</p>
                        </div>'
        ]);
    }

    public function down(){}
}
