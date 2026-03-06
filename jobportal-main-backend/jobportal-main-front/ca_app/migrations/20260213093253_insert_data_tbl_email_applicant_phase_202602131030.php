<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_email_applicant_phase_202602131030 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'send_email' => 'candidate_email',
                'subject' => 'Solicitud documentos contratación',
                'body' => ''
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

    public function down(){}
}
