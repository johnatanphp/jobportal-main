<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_email_applicant_phase_202410240824 extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'send_email' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_email_applicant_phase');

        // Crear la data con concatenación
        $data = [
            [
                'send_email' => 'employer_email',
                'subject' => 'Creación De Solicitud',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p>Tu solicitud {{request_id}}-{{job_title}} fue enviada exitosamente.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'recruitment_email',
                'subject' => 'Creación De Solicitud',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{recruitment_name}},</p>
                        <p>Se te asignó el proceso de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'employer_email',
                'subject' => 'Inicio De Proceso',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p>Tu solicitud {{request_id}}-{{job_title}} está siendo atendida por el Reclutador {{recruitment_name}}</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'candidate_email',
                'subject' => 'Registro En La Plataforma',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}},</p>
                        <p>¡Fuiste registrado en el Portal del Empleo de Overall!</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'candidate_email',
                'subject' => 'Registro En El Proceso',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}},</p>
                        <p>Estas siendo considerado para el puesto de "{{job_title}}" mantente atento que un reclutador se pondrá en contacto contigo.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'candidate_email',
                'subject' => 'Avance De Fase',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>Estimado/a {{candidate_name}},</p>
                        <p>estás pasando a la etapa de “entrevista” para el puesto de {{job_title}}.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'candidate_email',
                'subject' => 'Rechazado En El Proceso',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>{{candidate_name}} Gracias por participar en el proceso para el puesto de {{job_title}}, 
                        lamentablemente no fuiste seleccionado, mantendremos tu CV para futuras vacantes.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'candidate_email',
                'subject' => 'Inicio De Contratación',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Felicidades! {{candidate_name}} Por haber aprobado satisfactoriamente el proceso de selección para el puesto de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'candidate_email',
                'subject' => 'Candidato Contratado',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{candidate_name}} Bienvenido a la Familia Overall, éxitos desempeñando el puesto de: <b>{{job_title}}</b></p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
            [
                'send_email' => 'employer_email',
                'subject' => 'Candidato es contratado',
                'body' => '
                    <div style="padding: 10px 10px;">
                        <p>¡Hola! {{employer_name}},</p>
                        <p> El candidato <b>{{candidate_doc_number}}-{{candidate_name}}</b> para la vacante de <b>{{job_title}}</b> firmó su contrato.</p>
                        <br>
                        <p>Atentamente,</p>
                        <p>El equipo de selección</p>
                    </div>'
            ],
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

    public function down()
    {
        $this->dbforge->drop_table('tbl_email_applicant_phase');
    }
}
