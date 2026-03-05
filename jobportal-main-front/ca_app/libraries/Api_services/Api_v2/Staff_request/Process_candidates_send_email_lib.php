<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_send_email_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($params)
    {
        if (!isset($params['candidate_ids']) && !isset($params['request_id'])) {
            return [
                'status' => false,
                'message' => 'No se ha proporcionado candidate_ids o request_id'
            ];
        }
    
        if (!isset($params['template_id'])) {
            return [
                'status' => false,
                'message' => 'No se ha proporcionado template_id'
            ];
        }
    
        $template_id = $params['template_id'];

        // Consultar plantilla de correo
        $this->db->where('id', $template_id);
        $query = $this->db->get('tbl_email_applicant_phase');
        $template_email = $query->row();

        if (!$template_email) {
            return [
                'status' => false,
                'message' => 'El template_id proporcionado es incorrecto'
            ];
        }

        $result_data = [];

        if ($template_id == 1) {
            $result_data = $this->get_data_template_for_staff_request($params);
        } else if ($template_id == 2) {
            $result_data = $this->get_data_template_2($params);
        } else if ($template_id == 3) {
            $result_data = $this->get_data_template_for_staff_request($params);
        } else if ($template_id == 4) {
            $result_data = $this->get_data_template_4($params);
        } else {
            $result_data = $this->get_data_template($params);
        }
    
        foreach ($result_data as $candidate) {
            
            if (in_array($template_id, [5, 6, 7, 9]) && isset($candidate['job_slug'])) {
                $candidate['url_link'] = $candidate['job_slug'] ? site_url('jobs/' . $candidate['job_slug']) : site_url('login');
            }

            if (in_array($template_id, [8])) {
                $this->load->model('Recruitment_document_request');
                $url_link = $this->Recruitment_document_request->create_link($candidate['id'], $candidate['job_id']);
                
                if (!$url_link) {
                    continue;
                }
                
                $candidate['url_link'] = $url_link;
            }
          
            $this->prepare_and_send_email($candidate, $template_email, $candidate[$template_email->send_email]);
    
            // Enviar correo adicional al reclutador si `template_id` es `1`
            // if ($template_id == 1) {
            //     $this->db->where('id', 2);
            //     $query = $this->db->get('tbl_email_applicant_phase');
            //     $recruiter_template = $query->row();
            //     $this->prepare_and_send_email($candidate, $recruiter_template, $candidate[$recruiter_template->send_email]);
            // }    

            // Enviar correo adicional al solicitante si `template_id` es `9`
            if ($template_id == 9) {
                $this->db->where('id', 10);
                $query = $this->db->get('tbl_email_applicant_phase');
                $recruiter_template = $query->row();
                $this->prepare_and_send_email($candidate, $recruiter_template, $candidate[$recruiter_template->send_email]);
            }
        }
    
        return [
            'status' => true,
            'message' => 'Correos enviados con éxito'
        ];
    }
    
    private function get_data_template($params)
    {
        $candidate_ids = $params['candidate_ids'] ?? '';
        
        if (!is_array($candidate_ids)) {
            $candidate_ids = explode(',', $candidate_ids);
        }

        // Consulta de candidatos junto con los detalles adicionales
        $this->db->select([
            'seekers.ID AS id',
            'staff_requests.ID AS request_id',
            'staff_requests.job_title AS job_title',
            'stages.name AS stage_name',
            'seekers.document_number AS candidate_doc_number',
            "CONCAT(seekers.first_name, ' ', seekers.last_name) AS candidate_name",
            'seekers.email AS candidate_email',
            'app_users_employer.email AS employer_email',
            "CONCAT(
                IFNULL(app_users_employer.first_name, ''), 
                ' ', 
                IFNULL(app_users_employer.last_name, '')
            ) AS employer_name",
            'app_users_recruitment.email AS recruitment_email',
            "CONCAT(
                IFNULL(app_users_recruitment.first_name, ''), 
                ' ', 
                IFNULL(app_users_recruitment.last_name, '')
            ) AS recruitment_name",
            'jobs.ID AS job_id',
            'jobs.job_slug AS job_slug'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_employers app_users_employer', 'staff_requests.employer_ID = app_users_employer.ID', 'left');
        $this->db->join('tbl_employers app_users_recruitment', 'staff_requests.recruiter_ID = app_users_recruitment.ID', 'left');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID', 'left');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id', 'left');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=rs_candidates.job_ID', 'left');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID', 'left'); 
        $this->db->join('tbl_recruitment_stages stages', 'rs_candidates.stage = stages.id', 'left');

        if (isset($params['candidate_ids'])) {
            $this->db->where_in('seekers.id', $candidate_ids);
        }

        if (isset($params['request_id'])) {
            $this->db->where('staff_requests.id', $params['request_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('sr_process.id', $params['process_id']);
        }

        $this->db->group_by('seekers.id');

        return $this->db->get()->result_array();
    }

    private function get_data_template_for_staff_request($params)
    {
        $this->db->select([
            'staff_requests.ID AS request_id',
            'staff_requests.job_title AS job_title',
            'app_users_employer.email AS employer_email',
            "CONCAT(
                IFNULL(app_users_employer.first_name, ''), 
                ' ', 
                IFNULL(app_users_employer.last_name, '')
            ) AS employer_name",
            'app_users_recruitment.email AS recruitment_email',
            "CONCAT(
                IFNULL(app_users_recruitment.first_name, ''), 
                ' ', 
                IFNULL(app_users_recruitment.last_name, '')
            ) AS recruitment_name"
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_employers app_users_employer', 'staff_requests.employer_ID = app_users_employer.ID', 'left');
        $this->db->join('tbl_employers app_users_recruitment', 'staff_requests.recruiter_ID = app_users_recruitment.ID', 'left');
    
        if (isset($params['request_id'])) {
            $this->db->where_in('staff_requests.id', $params['request_id']);
        }

        return $this->db->get()->result_array();
    }

    private function get_data_template_2($params)
    {
        $this->db->select([
            'staff_requests.ID AS request_id',
            'staff_requests.job_title AS job_title',
            'app_users_employer.email AS employer_email',
            "CONCAT(
                IFNULL(app_users_employer.first_name, ''), 
                ' ', 
                IFNULL(app_users_employer.last_name, '')
            ) AS employer_name",
            'app_users_recruitment.email AS recruitment_email',
            "CONCAT(
                IFNULL(app_users_recruitment.first_name, ''), 
                ' ', 
                IFNULL(app_users_recruitment.last_name, '')
            ) AS recruitment_name"
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'staff_requests.ID = assigned_employers.request_ID');
        $this->db->join('tbl_employers app_users_employer', 'assigned_employers.employer_ID = app_users_employer.ID');
        $this->db->join('tbl_employers app_users_recruitment', 'staff_requests.recruiter_ID = app_users_recruitment.ID');
    
        if (isset($params['request_id'])) {
            $this->db->where_in('staff_requests.id', $params['request_id']);
        }

        return $this->db->get()->result_array();
    }

    private function get_data_template_4($params)
    {
        $candidate_ids = $params['candidate_ids'];
        
        if (!is_array($candidate_ids)) {
            $candidate_ids = explode(',', $candidate_ids);
        }

        $this->db->select([
            'seekers.id',
            'seekers.document_number AS candidate_doc_number',
            "CONCAT(seekers.first_name, ' ', seekers.last_name) AS candidate_name",
            'seekers.email AS candidate_email'
        ]);
        
        $this->db->from('tbl_job_seekers seekers'); 
        if (isset($params['candidate_ids'])) {
            $this->db->where_in('seekers.id', $candidate_ids);
        }

        return $this->db->get()->result_array();
    }

    // Función auxiliar para enviar correo
    private function prepare_and_send_email($candidate, $template_email, $email)
    {
        $body = str_replace([
            '{{request_id}}', 
            '{{employer_name}}', 
            '{{job_title}}',
            '{{stage_name}}', 
            '{{recruitment_name}}', 
            '{{candidate_name}}', 
            '{{candidate_doc_number}}',
            '{{url_link}}',
        ],
        [
            $candidate['request_id'] ?? '', 
            $candidate['employer_name'] ?? '', 
            $candidate['job_title'] ?? '', 
            $candidate['stage_name'] ?? '',
            $candidate['recruitment_name'] ?? '', 
            $candidate['candidate_name'] ?? '', 
            $candidate['candidate_doc_number'] ?? '',
            $candidate['url_link'] ?? site_url('login')
        ],
            $template_email->body
        );
    
        $data_email = [
            'body' => $body
        ];

        // Configuración de correo
        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($email);
    
        $mail_message = load_email_view('email/notify_stage_candidate', $data_email);

        $this->email->subject($template_email->subject);
        $this->email->message($mail_message);
        $this->email->send();
    }
}
