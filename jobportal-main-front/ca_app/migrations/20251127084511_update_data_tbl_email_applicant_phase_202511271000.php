<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_email_applicant_phase_202511271000 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'id' => 4,
                'send_email' => 'candidate_email',
                'subject' => 'Registro En La Plataforma',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola 👋🏻, {{candidate_name}}!</p>
                        <br>
                        <p>¡Fuiste registrado en el Portal del Empleo de Overall! 💙</p>
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
                        <p>¡Hola 👋🏻, {{candidate_name}}!</p>
                        <br>
                        <p>Estas siendo considerado para el puesto de "{{job_title}}" mantente atento que un reclutador se pondrá en contacto contigo. 😄</p>
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
                        <p>¡Felicitaciones {{candidate_name}}! 🥳</p>
                        <br>
                        <p>Estás pasando a la etapa de “{{stage_name}}” para el puesto de {{job_title}}. 💙</p>
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
                        <p>Hola ${candidate_name} 👋🏻, gracias por tu participación en el proceso de selección.</p>
                        <br>
                        <p>Después de evaluar todos los perfiles, en esta ocasión no fuiste seleccionado/a. 😞</p>
                        <p>Agradecemos tu interés y el tiempo que dedicaste. Guardaremos tus datos para futuras oportunidades.</p>
                        <br>
                        <p>¡Te deseamos muchos éxitos! 💙</p>
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
                        <p>¡Hola {{candidate_name}}! 😊</p>
                        <br>
                        <p>Nos alegra informarte que has sido seleccionado/a para continuar en el proceso para integrar nuestro equipo.</p>
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
                        <p>🎉 ¡Bienvenido/a a Overall, {{candidate_name}}!</p>
                        <br>
                        <p>Estamos muy felices de contar contigo y esperamos que esta nueva etapa sea una gran experiencia para tu crecimiento personal y profesional.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>
                    <div class="wrapper-paragraph">
                        <a href="{{url_link}}">Ver detalle</a>
                    </div>'
            ]
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
