<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_request_start_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function start($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => false,
                'message' => $message
            ];
        }
        
        $request_id = $params['request_id'];

        $staff_request = $this->Staff_request->find($request_id);

        $this->db->trans_start();

        $this->db->where('ID', $request_id);
        $trans_status = $this->db->update('tbl_staff_requests', [
            'sts_process' => '9' //Marcar como iniciado
        ]);

        if (!$trans_status) {
          return [
            'status' => false,
            'message' => 'No se pudo iniciar los procesos de la solicitud'
          ];
        }

        $this->db->from('tbl_post_jobs');
        $this->db->where('request_ID', $request_id);
        $job = $this->db->get()->row();

        if ($job) {
            $this->db->where('request_id', $request_id);
            $this->db->update('tbl_recruitment_process', [
                'job_ID' => $job->ID,
                'sts_stage' => 8,
                'sts' => 'active',
                'tray_type_id' => 2
            ]);
        }

        $assign_responsible_automatically = $params['assign_responsible_automatically'] ?? 0;

        if ($assign_responsible_automatically == 1) {
            //Asignar responsables
            $this->Staff_request->assign_employers_responsibles($staff_request->ID);
        }

        //Asignar RRHH
        //$this->Staff_request->assign_rrhh_responsibles($staff_request->ID);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return [
                'status' => false,
                'message' => 'Error al iniciar la solicitud'
            ];
        }
        
        $send_email_recruiter = $params['send_email_recruiter'] ?? 0;

        if ($send_email_recruiter == 1) {
            $this->send_email_recruiter($staff_request);
        }

        $send_email_responsibles = $params['send_email_responsibles'] ?? 0;

        if ($assign_responsible_automatically && $send_email_responsibles == 1) {
            $this->send_emails_employer_responsibles($staff_request);
        }

        $response_data = [
            'id' => $request_id
        ];

        return apiv2_response(
            true, 
            'Solicitud ha sido iniciada', 
            $response_data
        );
    }

    public function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('request_id', 'request_id', 'trim|required|numeric');
        $this->form_validation->set_rules('assign_responsible_automatically', 'assign_responsible_automatically', 'trim|in_list[1,0]');

        $this->form_validation->set_message('required', 'El campo %s es requerido');
      
        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        $staff_request = $this->Staff_request->find($params['request_id']);

        if (!$staff_request || $staff_request->request_model_id != 4) {
            return [false, 'Solicitud es incorrecta'];
        }

        if ($staff_request->sts_process != '8') {
            return [false, 'Para poder iniciar la solicitud debe estar Sin Iniciar'];
        }

        $assign_responsible_automatically = $params['assign_responsible_automatically'] ?? 0;

        // if ($assign_responsible_automatically == 1) {
        //     $employer_permissions = $this->get_employer_responsibles_permissions($staff_request);

        //     if (count($employer_permissions) == 0) {
        //         return [false, 'No hay responsables de RyS para asignar a la solicitud'];
        //     }
        // }

        return [true, 'OK'];
    }

    private function send_emails_employer_responsibles($staff_request)
    {    
        $this->db->select([
            'employers.email'
        ]);
        $this->db->from('tbl_employers employers');   
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.employer_ID=employers.ID');
        $this->db->where('assigned_employers.request_ID', $staff_request->ID);
    
        $employers = $this->db->get()->result();

        $emails = [];

        foreach ($employers as $row_employer) {
            $emails[] = $row_employer->email;
        }

        if (count($emails) == 0) {
            return;
        }

        $recruiter = $this->Employer->find($staff_request->recruiter_ID);
        
        $this->load->library('Mail_template/Recruitment_process/Recruitment_process_assignment_responsibles');

        $mail_vars = $this->recruitment_process_assignment_responsibles->build([
            'employer_name' => 'Estimado/a',
            'job_title' => $staff_request->job_title,
            'url_link' => site_url('login')
        ]);

        $this->email->init();
        $this->email->to($emails);
        
        if ($recruiter->email) {
            $this->email->cc($recruiter->email);
        }
        
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }

    private function send_email_recruiter($staff_request)
    {    
        $recruiter = $this->Employer->find($staff_request->recruiter_ID);
        $emails[] = $recruiter->email;

        if (count($emails) == 0) {
            return;
        }
        
        $this->load->library('Mail_template/Recruitment_process/Recruitment_process_start');

        $mail_vars = $this->recruitment_process_start->build([
            'employer_name' => $recruiter->first_name,
            'request_id' => $staff_request->ID,
            'job_title' => $staff_request->job_title,
            'url_link' => site_url('login')
        ]);

        $this->email->init();
        $this->email->to($emails);
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }
}
