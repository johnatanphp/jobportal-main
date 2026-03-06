<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_move_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function move($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }
     
        return  $this->move_candidate($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('process_id', 'process_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('candidate_ids[]', 'candidate_ids[]', 'required');
        $this->form_validation->set_rules('stage_id', 'stage_id', 'required|integer|greater_than[0]');
        //$this->form_validation->set_rules('user_id', 'user_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('send_email_candidate', 'send_email_candidate', 'in_list[1,0]');
        $this->form_validation->set_rules('send_whatsapp_candidate', 'send_whatsapp_candidate', 'in_list[1,0]');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        $process_row = $this->get_data_by_process_id($params['process_id']);

        if (!$process_row) {
            return [false, 'Proceso no encontrado'];
        }

        if ($process_row->request_sts_process != '9') {
            return [false, 'El proceso pertenece a una solicitud que no ha sido iniciada'];
        }

        if (!$process_row->job_id) {
            return [false, 'El proceso no tiene un job_id vinculado'];
        }

        // $employer = $this->Employer->find($params['user_id']);

        // if (!$employer) {
        //     return [false, 'El valor user_id no se encontro'];
        // }

        $this->db->from('tbl_recruitment_stages');
        $this->db->where('stage_group_id', $process_row->request_stage_group_id);
        $this->db->where('id', $params['stage_id']);
        $stage_row = $this->db->get()->row();

        if (!$stage_row) {
            return [false, 'La etapa ingresada no existe o no pertenece al grupo de etapas de la solicitud'];
        }

        $candidate_ids = $params['candidate_ids'];

        $this->db->from('tbl_recruitment_candidates');
        $this->db->where('process_id', $params['process_id']);
        $this->db->where_in('seeker_ID', $candidate_ids);
        $count_candidates = $this->db->count_all_results();

        if ($count_candidates == 0) {
            return [false, 'Los postulantes ingresados no existen en el proceso indicado'];
        }

        if ($count_candidates != count($candidate_ids)) {
            return [false, 'Hay postulantes que no se encontraron en el proceso indicado'];
        }
        
        return [true, 'OK'];
    }

    public function move_candidate($params)
    {
        $this->db->where('process_id', $params['process_id']);
        $this->db->where_in('seeker_ID', $params['candidate_ids']);
        $updated_candidates = $this->db->update('tbl_recruitment_candidates', [
            'stage' => $params['stage_id'],
            'update_date' => date('Y-m-d H:i:s')
        ]);

        if ($updated_candidates === false) {
            return [
                'status' => false,
                'message' => 'No se pudo actualizar a los candidatos de etapa'
            ];
        }
        
        if ($params['stage_id'] == 17) {
            
            // Registrar postulantes en bandeja de contratacion
            $this->register_tray_candidates($params);
            // Enviar notificaciones a gestores de nomina
            $this->send_notification_rrhh_employers($params);
        }

        // Enviar notificaciones a los candidatos por Email y WhatsApp
        $this->send_notification_candidates($params);
        
        return [
            'status' => true,
            'message' => 'Candidatos han sido movidos'
        ];  
    }
    
    public function send_notification_rrhh_employers($params)
    {
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_tray_candidate');
        $this->load->model('Workflow_client');
        
        $process_id = $params['process_id'];
        $candidate_ids = $params['candidate_ids'];
        
        $process = $this->Recruitment_process->find($process_id);
        
        $staff_request = $this->Staff_request->find($process->request_id);
        
        $client = $this->Workflow_client->find(['code' => $staff_request->cod_clie, 'company_id' => $staff_request->company_ID]);
        
        $this->db->select([
            'employer.email AS email'
        ]);
        $this->db->from('tbl_employers employer');
        $this->db->join('tbl_employer_profiles employer_profiles', 'employer_profiles.user_id=employer.ID');
        $this->db->join('tbl_profile_actions_permissions profile_actions_permissions', 'profile_actions_permissions.employer_id=employer.ID AND employer_profiles.profile_id=profile_actions_permissions.profile_id');
        $this->db->join('tbl_modules_actions modules_actions', 'modules_actions.id=profile_actions_permissions.action_id');
        $this->db->join('tbl_employer_permission_clients client_companies', 'client_companies.employer_id=employer.ID');
        $this->db->where('client_companies.consultant_code', $staff_request->no_cia);
        $this->db->where('client_companies.client_code', $staff_request->cod_clie);       
        $this->db->where('employer_profiles.profile_id', 3); // Perfil gestor de nomina
        $this->db->where('modules_actions.keyword_id',  'hire_candidates'); // Permiso de contratar candidatos        
        $this->db->where('employer.company_ID', $staff_request->company_ID);
        $this->db->where('employer.sts', 'active');
        
        $this->db->group_by('employer.ID');
        
        $result_employers = $this->db->get()->result();
        
        $emails = [];
        
        foreach ($result_employers as $employer) {
            $emails[] = $employer->email;
        }
        
        if (count($emails) == 0) {
            return;
        }
        
        $employer = $this->Employer->find($this->session_employer_lib->get_data('user_id'));
        
        $candidates = $this->Recruitment_tray_candidate->get_all_candidates_by_process_id($process_id, $candidate_ids);
        $sent_at = date('Y-m-d H:i:s');
        
        $data_email = [
            'url_link' => site_url('employer/recruitment_tray/process_candidates_list/index/' . $client->code),
            'comments' => '',
            'sent_at' => $sent_at,
            'sent_by' => $employer,
            'candidates' => $candidates,
            'staff_request' => $staff_request
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);

        $mail_message = load_email_view('email/tray_candidates/notify_sent_candidates', $data_email);

        $this->email->subject('Candidatos enviados a contratación - ' . $client->name);
        $this->email->message($mail_message);     
        $this->email->send();
    }
    
    private function get_data_by_process_id($process_id = 0)
    {
        $this->db->select([
            'jobs.ID AS job_id',
            'jobs.job_title AS job_title',
            'jobs.job_slug AS job_slug',
            'requests.ID AS request_id',
            'requests.sts_process AS request_sts_process',
            'requests.stage_group_id AS request_stage_group_id'
        ]);
        $this->db->from('tbl_post_jobs jobs');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=jobs.request_ID');
        $this->db->join('tbl_recruitment_process process', 'process.request_id=requests.ID');
        $this->db->where('process.id', $process_id);

        return $this->db->get()->row();
    }

    private function get_data_candidate($params)
    {
        $process_id = $params['process_id'];
        $candidate_ids = $params['candidate_ids'];
        $stage_id = $params['stage_id'];

        $this->db->select([
            'rc.process_id AS process_id',
            'rc.job_ID AS job_id',
            'candidate.ID AS candidate_id',
            'candidate.email AS candidate_email',
            'candidate.first_name AS candidate_first_name',
            'candidate.mobile AS candidate_mobile',
            'stages.name AS stage_name'
        ]);
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->join('tbl_job_seekers candidate', 'candidate.Id=rc.seeker_ID');
        $this->db->join('tbl_recruitment_stages stages', 'stages.id=rc.stage');
        $this->db->where('rc.process_id', $process_id);
        $this->db->where('rc.stage', $stage_id);
        
        $this->db->where_in('rc.seeker_ID', $candidate_ids);

        return $this->db->get()->result();
    }

    private function send_notification_candidates($params)
    {
        $stage_id = $params['stage_id'];
        $result_candidates = $this->get_data_candidate($params);

        $send_email_candidate = $params['send_email_candidate'] ?? 0;

        if ($send_email_candidate) {

            if ($stage_id == 17) { //Enviar a contratacion
                $this->send_email_candidate_to_hire($result_candidates, $params);
            } else {
                $this->send_email_candidate($result_candidates, $params);
            }
        }

        $send_whatsapp_candidate = $params['send_whatsapp_candidate'] ?? 0;

        if ($send_whatsapp_candidate) {
            if ($stage_id == 17) { //Enviar a contratacion
                $this->send_whatsapp_candidate_to_hire($result_candidates, $params);
            } else {
                $this->send_whatsapp_candidate($result_candidates, $params);
            }
        }
    }

    private function send_email_candidate($result_candidates, $params)
    {  
        $this->load->library('queue_lib');
        $process_id = $params['process_id'];
        $process = $this->get_data_by_process_id($process_id);

        foreach ($result_candidates as $rc_row) {
            
            $this->queue_lib->push('Notify_candidate_process_move_job', [
                'candidate_id' => $rc_row->candidate_id,
                'request_id' => $process->request_id,
                'notify_by_whatsapp' => 0,
                'notify_by_mail' => 1
            ]);
        } 
    }
    
    private function send_whatsapp_candidate($result_candidates, $params)
    {
        $this->load->library('queue_lib');
        $process_id = $params['process_id'];
        $process = $this->get_data_by_process_id($process_id);

        foreach ($result_candidates as $rc_row) {
            
            $this->queue_lib->push('Notify_candidate_process_move_job', [
                'candidate_id' => $rc_row->candidate_id,
                'request_id' => $process->request_id,
                'notify_by_whatsapp' => 1,
                'notify_by_mail' => 0
            ]);
        }
    }

    private function send_email_candidate_to_hire($result_candidates, $params)
    {        
        $this->load->library('queue_lib');
        $process_id = $params['process_id'];
        $process = $this->get_data_by_process_id($process_id);

        foreach ($result_candidates as $rc_row) {
            
            $this->queue_lib->push('Notify_candidate_hired_job', [
                'candidate_id' => $rc_row->candidate_id,
                'request_id' => $process->request_id,
                'notify_whatsapp' => 0
            ]);
        }    
    }
    
    private function send_whatsapp_candidate_to_hire($result_candidates, $params)
    {
        $this->load->library('queue_lib');
        $process_id = $params['process_id'];
        $process = $this->get_data_by_process_id($process_id);

        foreach ($result_candidates as $rc_row) {
            
            $this->queue_lib->push('Notify_candidate_hired_job', [
                'candidate_id' => $rc_row->candidate_id,
                'request_id' => $process->request_id,
                'notify_whatsapp' => 1,
                'notify_email' => 0,
            ]);
        }
    }

    private function register_tray_candidates($params)
    {
        $process_id = $params['process_id'];
        $candidates = $params['candidate_ids'];

        $this->db->select([
            'requests.cod_clie AS client_code',
            'requests.company_ID AS company_id'
        ]);
        $this->db->from('tbl_recruitment_process rc_process');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=rc_process.request_id');
        $this->db->where('rc_process.id', $process_id);
        $rc_process = $this->db->get()->row();

        $this->db->select([
            'seeker_id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates');
        $this->db->where('process_id', $process_id);
        $this->db->where_in('seeker_id', $candidates);
        $result_seeker_ids = $this->db->get()->result_array();

        $list_seeker_ids = array_column($result_seeker_ids, 'seeker_id');

        $inserts = [];
    
        foreach ($candidates as $candidate_id) {
            if (!in_array($candidate_id, $list_seeker_ids)) {
                $inserts[] = [
                    'process_id' => $process_id,
                    'seeker_id' => $candidate_id,
                    'status_id' => 2, //Estado Enviado a contratación
                    'client_code' => $rc_process->client_code,
                    'company_id' => $rc_process->company_id,
                    'created_by' => $this->session_employer_lib->get_data('user_id')
                ];
            }
        }

        //Registar data
        if (count($inserts) > 0) {
            $this->db->insert_batch('tbl_recruitment_tray_candidates', $inserts);
        }
    }
}
