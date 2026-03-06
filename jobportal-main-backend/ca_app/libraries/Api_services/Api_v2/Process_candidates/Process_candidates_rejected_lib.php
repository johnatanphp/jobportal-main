<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_rejected_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function reject($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return apiv2_response(
                false, 
                $message
            );
        }
     
        return $this->rejected_candidate($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('process_id', 'process_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('candidate_id', 'candidate_ids[]', 'required');
        //$this->form_validation->set_rules('rejected_by_user', 'rejected_by_user', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('comments', 'comments', 'required');
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

        // $employer = $this->Employer->find($params['rejected_by_user']);

        // if (!$employer) {
        //     return [false, 'El valor user_id no se encontro'];
        // }

        $candidate_id = $params['candidate_id'];

        $this->db->from('tbl_recruitment_candidates');
        $this->db->where('process_id', $params['process_id']);
        $this->db->where('seeker_ID', $candidate_id);
        $count_candidates = $this->db->count_all_results();

        if ($count_candidates == 0) {
            return [false, 'Los postulantes ingresados no existen en el proceso indicado'];
        }
        
        return [true, 'OK'];
    }

    public function rejected_candidate($params)
    {
        $employer_id = $this->session_employer_lib->get_data('user_id');
        $employer = $this->Employer->find($employer_id);

        $rejected_date = date('Y-m-d');
		$rejected_time = date('H:i:s');

        $data_update = [
            'discarded' => 1,
            'comments' => $params['comments'],
            'rejected_date' => $rejected_date,
            'rejected_time' => $rejected_time,
            'rejected_by_user' => $employer->ID
        ];

        $this->db->where('process_id', $params['process_id']);
        $this->db->where('seeker_ID', $params['candidate_id']);

        $trans_update = $this->db->update('tbl_recruitment_candidates', $data_update);
        
        if (!$trans_update) {
            return [
                'status' => false,
                'message' => 'Error al descartar el candidato'
            ];  
        }

        //Enviar notificaciones a los candidatos por Email y WhatsApp
        $this->send_notification_candidates($params);
        
        return apiv2_response(
            true, 
            'Candidato ha sido descartado'
        );
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

    private function send_notification_candidates($params)
    {
        $send_email_candidate = $params['send_email_candidate'] ?? 0;

        if ($send_email_candidate == 1) {
            $this->send_email_candidate($params);
        }

        $send_whatsapp_candidate = $params['send_whatsapp_candidate'] ?? 0;

        if ($send_whatsapp_candidate == 1) {
            $this->send_whatsapp_candidate($params);
        }
    }

    private function send_email_candidate($params)
    {
        $process_id = $params['process_id'];
        $process = $this->get_data_by_process_id($process_id);
        $candidate = $this->Job_seeker->find($params['candidate_id']);

        $this->load->library('Mail_template/Recruitment_process/Recruitment_process_reject_candidate');

        $mail_vars = $this->recruitment_process_reject_candidate->build([
            'candidate_name' => $candidate->first_name,
            'job_title' => $process->job_title,
            'url_link' => $process->job_slug ? site_url('jobs/' . $process->job_slug) : site_url('login')
        ]);

        $emails = $candidate->email;

        $this->email->init();
        $this->email->to($emails);
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }

    private function send_whatsapp_candidate($params)
    {
        $process_id = $params['process_id'];
        $candidate_id = $params['candidate_id'];

        $process = $this->get_data_by_process_id($process_id);
        
        $this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_reject_lib');
        $this->whatsapp_recruitment_proccess_candidate_reject_lib->send($process->request_id, $candidate_id);
    }
}
