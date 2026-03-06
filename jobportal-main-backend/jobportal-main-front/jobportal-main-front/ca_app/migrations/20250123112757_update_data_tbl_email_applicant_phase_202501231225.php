<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_email_applicant_phase_202501231225 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'id' => 5,
                'send_email' => 'candidate_email',
                'subject' => 'Registro En El Proceso',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}},</p>
                        <p>Estas siendo considerado para el puesto de "{{job_title}}" mantente atento que un reclutador se pondrá en contacto contigo.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Ver detalle</a>
                    </div>'
            ],
            [
                'id' => 6,
                'send_email' => 'candidate_email',
                'subject' => 'Avance De Fase',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>Estimado/a {{candidate_name}},</p>
                        <p>estás pasando a la etapa de “{{stage_name}}” para el puesto de {{job_title}}.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Ver detalle</a>
                    </div>'
            ],
            [
                'id' => 7,
                'send_email' => 'candidate_email',
                'subject' => 'Rechazado En El Proceso',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>{{candidate_name}} Gracias por participar en el proceso para el puesto de {{job_title}}, 
                        lamentablemente no fuiste seleccionado, mantendremos tu CV para futuras vacantes.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Ver detalle</a>
                    </div>'
                    
            ],
            [
                'id' => 8,
                'send_email' => 'candidate_email',
                'subject' => 'Inicio De Contratación',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Felicidades! {{candidate_name}} Por haber aprobado satisfactoriamente el proceso de selección para el puesto de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Por favor ingresa al siguiente link y registra todos los documentos necesarios para realizar la contratación.</p>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Cargar documentos</a>
                    </div>'
            ],
            [
                'id' => 9,
                'send_email' => 'candidate_email',
                'subject' => 'Proceso De Contratación',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}} Estas iniciando el proceso de contratación para el puesto de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Ver detalle</a>
                    </div>'
            ],
            [
                'id' => 10,
                'send_email' => 'employer_email',
                'subject' => 'Candidato Registrado En Sistema',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p>Los datos del candidato <b>{{candidate_doc_number}}-{{candidate_name}}</b>, para la vacante de <b>{{job_title}}</b> 
                        han sido registrados en el sistema de nómina para completar su proceso de contratación.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al portal</a>
                    </div>'
            ],
        ];

        foreach ($data as $row) {
            $body = $row['body'];
            $id = $row['id'];
            
            $this->db->where('id', $id);
            $this->db->update('tbl_email_applicant_phase', [
                'body' => $body,
                'subject' => $row['subject']
            ]);
        }
    }

    public function down(){}
}
