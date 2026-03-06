<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_email_applicant_phase_202501161015 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'id' => 1,
                'send_email' => 'employer_email',
                'subject' => 'Creación De Solicitud',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p>Tu solicitud {{request_id}}-{{job_title}} fue enviada exitosamente.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
            [
                'id' => 2,
                'send_email' => 'recruitment_email',
                'subject' => 'Creación De Solicitud',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{recruitment_name}},</p>
                        <p>Se te asignó el proceso de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
            [
                'id' => 3,
                'send_email' => 'employer_email',
                'subject' => 'Inicio De Proceso',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p>Tu solicitud {{request_id}}-{{job_title}} está siendo atendida por el Reclutador {{recruitment_name}}</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
            [
                'id' => 4,
                'send_email' => 'candidate_email',
                'subject' => 'Registro En La Plataforma',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}},</p>
                        <p>¡Fuiste registrado en el Portal del Empleo de Overall!</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
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
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
            [
                'id' => 6,
                'send_email' => 'candidate_email',
                'subject' => 'Avance De Fase',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>Estimado/a {{candidate_name}},</p>
                        <p>estás pasando a la etapa de “entrevista” para el puesto de {{job_title}}.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
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
                        <a href="{{url_link}}">Acceder al Portal</a>
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
                'subject' => 'Candidato Contratado',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}} Bienvenido a la Familia Overall, éxitos desempeñando el puesto de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
            [
                'id' => 10,
                'send_email' => 'employer_email',
                'subject' => 'Candidato es contratado',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p> El candidato <b>{{candidate_doc_number}}-{{candidate_name}}</b> para la vacante de <b>{{job_title}}</b> firmó su contrato.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Acceder al Portal</a>
                    </div>'
            ],
        ];

        foreach ($data as $row) {
            $body = $row['body'];
            $id = $row['id'];
            
            $this->db->where('id', $id);
            $this->db->update('tbl_email_applicant_phase', [
                'body' => $body
            ]);
        }
    }

    public function down(){}
}
