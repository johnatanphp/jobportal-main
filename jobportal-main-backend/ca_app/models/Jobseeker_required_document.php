<?php
class Jobseeker_required_document extends CI_Model {

    public function save_domicile_affidavit($seeker_id, $file_name)
    {/*
        $document_id = 4;

        $this->delete_document($seeker_id, $document_id);

        $result = $this->register_document(
            $seeker_id, 
            $document_id,
            $file_name
        );
        */

        $this->db->where('seeker_id', $seeker_id);
        $this->db->delete('tbl_seeker_domicile_affidavits');

        $data_insert = [
            'seeker_id' => $seeker_id,
            'file_path' => $file_name
        ];
        return $this->db->insert('tbl_seeker_domicile_affidavits', $data_insert);
    }

    public function get_domicile_affidavit($seeker_id)
    {
        /*
        $document_id = 4;

        $document = $this->get_documents(
            $seeker_id, 
            $document_id
        );
        
        return current($document);

        */
        $this->db->select([
            'seeker_id AS seeker_ID',
            'file_path AS attach_file_name',
            'evicertia_status',
            'evicertia_unique_id'
        ]);
        $this->db->from('tbl_seeker_domicile_affidavits');
        $this->db->where('seeker_id', $seeker_id);

        $this->db->order_by('id', 'DESC');
        
        return $this->db->get()->row();
    }

    public function save_police_records($seeker_id, $file_name)
    {/*
        $document_id = 5;

        $this->delete_document($seeker_id, $document_id);

        $result = $this->register_document(
            $seeker_id, 
            $document_id,
            $file_name
        );

        return $result;
        //tbl_seeker_police_records
*/
        $this->db->where('seeker_id', $seeker_id);
        $this->db->delete('tbl_seeker_police_records');

        $data_insert = [
            'seeker_id' => $seeker_id,
            'file_path' => $file_name
        ];
        return $this->db->insert('tbl_seeker_police_records', $data_insert);

    }

    public function get_police_records($seeker_id)
    {/*
        $document_id = 5;

        $document = $this->get_documents(
            $seeker_id, 
            $document_id
        );
        
        return current($document);
        */

        $this->db->select([
            'seeker_id AS seeker_ID',
            'file_path AS attach_file_name'
        ]);
        $this->db->from('tbl_seeker_police_records');
        $this->db->where('seeker_id', $seeker_id);
        return $this->db->get()->row();
    }

    public function save_declaration_5th_category($seeker_id, $file_name)
    {/*
        $document_id = 6;

        $this->delete_document($seeker_id, $document_id);

        $result = $this->register_document(
            $seeker_id, 
            $document_id,
            $file_name
        );

        return $result;
        */
        $this->db->where('seeker_id', $seeker_id);
        $this->db->delete('tbl_seeker_declaration_5th_categories');

        $data_insert = [
            'seeker_id' => $seeker_id,
            'file_path' => $file_name
        ];
        return $this->db->insert('tbl_seeker_declaration_5th_categories', $data_insert);
    }

    public function get_declaration_5th_category($seeker_id)
    {/*
        $document_id = 6;

        $document = $this->get_documents(
            $seeker_id, 
            $document_id
        );
        
        return current($document);
*/
        $this->db->select([
            'seeker_id AS seeker_ID',
            'file_path AS attach_file_name',
            'evicertia_unique_id',
            'evicertia_status',
        ]);
        $this->db->from('tbl_seeker_declaration_5th_categories');
        $this->db->where('seeker_id', $seeker_id);

        $this->db->order_by('id', 'DESC');

        return $this->db->get()->row();
    }

    public function save_certificate_5th_category($seeker_id, $file_name)
    {
        /*
        $document_id = 7;

        $this->delete_document($seeker_id, $document_id);

        $result = $this->register_document(
            $seeker_id, 
            $document_id,
            $file_name
        );

        return $result;

        */

        $this->db->where('seeker_id', $seeker_id);
        $this->db->delete('tbl_seeker_certificate_5th_categories');

        $data_insert = [
            'seeker_id' => $seeker_id,
            'file_path' => $file_name
        ];
        return $this->db->insert('tbl_seeker_certificate_5th_categories', $data_insert);
    }

    public function get_certificate_5th_category($seeker_id)
    {/*
        $document_id = 7;

        $document = $this->get_documents(
            $seeker_id, 
            $document_id
        );
        
        return current($document);

        */
        $this->db->select([
            'seeker_id AS seeker_ID',
            'file_path AS attach_file_name'
        ]);
        $this->db->from('tbl_seeker_certificate_5th_categories');
        $this->db->where('seeker_id', $seeker_id);
        return $this->db->get()->row();

    }

    public function get_recruitment_document(
        $seeker_id, 
        $document_id,
        $ref_id = null,
        $job_id = null
    )
    {
        return $this->db->get_where('tbl_recruitment_seeker_documents', [
            'seeker_ID' => $seeker_id,
            'document_id' => $document_id,
            'ref_id' => $ref_id, 
            'job_id' => $job_id 
        ])
        ->row();
    }

    public function change_approval_document($data)
    {
        $data['ref_id'] = isset($data['ref_id']) ? $data['ref_id'] : null;

        $rs_document = $this->db->get_where('tbl_recruitment_seeker_documents', [
            'seeker_ID' => $data['seeker_id'],
            'document_id' => $data['document'],
            'ref_id' => $data['ref_id'], 
            'job_id' => $data['job_id'] 
        ])
        ->row();

        if ($this->session->userdata('current_profile_id') == 3) {
            $data_approved['approved'] = $rs_document && $rs_document->approved ? 0 : 1;
        }

        if ($this->session->userdata('current_profile_id') == 5) {
            $data_approved['legal_approved'] = $rs_document && $rs_document->legal_approved ? 0 : 1;
        }

        if ($this->session->userdata('current_profile_id') == 6) {
            $data_approved['accounting_approved'] = $rs_document && $rs_document->accounting_approved ? 0 : 1;
        }

        if ($rs_document) {
            $this->db->where('id', $rs_document->ID);  
            return $this->db->update('tbl_recruitment_seeker_documents', $data_approved);
        }
        
        $data_approved['seeker_ID'] = $data['seeker_id'];
        $data_approved['document_id'] = $data['document'];
        $data_approved['ref_id'] = $data['ref_id'];
        $data_approved['job_id'] = $data['job_id'];
        
        return $this->db->insert('tbl_recruitment_seeker_documents', $data_approved);
    }  

    public function save_document_comments($data)
    {
        $data['ref_id'] = isset($data['ref_id']) ? $data['ref_id'] : null;

        $rs_document = $this->db->get_where('tbl_recruitment_seeker_documents', [
            'seeker_ID' => $data['candidate_id'],
            'document_id' => $data['document_id'],
            'ref_id' => $data['ref_id'], 
            'job_id' => $data['job_id'] 
        ])
        ->row();

        if ($rs_document) {
            $data_comments = [
                'comments' => trim($data['comments'])
            ];

            $this->db->where('id', $rs_document->ID);            
            $status = $this->db->update('tbl_recruitment_seeker_documents', $data_comments);
        } else {
              $data_comments = [
                'seeker_ID' => $data['candidate_id'],
                'document_id' => $data['document_id'],
                'ref_id' => $data['ref_id'],
                'job_id' => $data['job_id'],
                'comments' => trim($data['comments'])
            ];

            $status = $this->db->insert('tbl_recruitment_seeker_documents', $data_comments);
        }

        if ($status) {
            //Enviar correo notificando el comentario al candidato
            $this->send_notification_comments_to_candidate_by_email(
                $data['candidate_id'], 
                $data['document_id'],
                $data['job_id'],
                $data['ref_id']
            );
        }

        return $status;
    }

    public function send_notification_comments_to_candidate_by_email(
        $candidate_id = 0, 
        $document_key = 0,
        $job_id = 0,
        $ref_id = null
    )
    {
        $this->load->model('Recruitment_contract_document_type');

        $document = $this->Recruitment_contract_document_type->find($document_key);

        $rs_document = $this->db->get_where('tbl_recruitment_seeker_documents', [
            'seeker_ID' => $candidate_id,
            'document_id' => $document_key,
            'ref_id' => $ref_id, 
            'job_id' => $job_id 
        ])
        ->row();
        
        $row_candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);
        
        $document_name =  $document->name;

        $data_email = [
            'name' => $row_candidate->first_name,
            'document' => $document_name,
            'comments' => $rs_document->comments 
        ];

        $config = $this->Email_drafts->email_configuration();

        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($row_candidate->email);

        $result_assignments = [];

        if ($this->session->userdata('current_profile_id') == 5) {
            $this->db->select('users.email');
            $this->db->from('tbl_recruitment_rrhh_assignments assignments');
            $this->db->join('tbl_employers users', 'assignments.rrhh_user_ID=users.ID');
            $this->db->where('assignments.job_ID', $job_id);

            $result_assignments = $this->db->get()->result();
        }

        $email_bcc = [];

        foreach ($result_assignments as $row) {
            $email_bcc[$row->email] = $row->email;
        }
        
        if (!empty($email_bcc)) {
            $this->email->bcc($email_bcc);
        } 

        $mail_message = load_email_view('email/rs_document_comments', $data_email);

        $this->email->subject('Solicitud de documentos - ' . $document_name);
        $this->email->message($mail_message);     
        //Send email
        $this->email->send();
                
        // if ($document_key == 'rys_form_affidavit') {
        //     $this->load->model('Rys_form_seeker');
        //     $form_assignment = $this->Rys_form_seeker->get_assignment($ref_id);
        //     $document_name.= ': ' . $form_assignment->form_name;
        // }
    }
}
