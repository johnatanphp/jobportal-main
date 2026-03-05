<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_email_staff_request_lib {

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function notify_creation($request_id)
    {
        $to_email = $this->Company_account_setting->item('email_notify_create_staff_request');

        if (trim($to_email) == '') {
            return;
        }

        $staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

        $staff_recruiter = $this->Employer->find($staff_request->recruiter_ID);

        $data_email = array(
            'staff_recruiter' => $staff_recruiter,
            'staff_request' => $staff_request
        );

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($to_email);

        $mail_message = load_email_view('email/staff_request_created', $data_email);

        $this->email->subject('Solicitud personal creada');
        $this->email->message($mail_message);     
        $this->email->send();
    }

    public function notify_creation_gantt_activities(
        $request_id, 
        $type_gantt,
        $destinataries = array()
    )
    {
        $this->load->library('gantt_request_excel');

        $staff_request = $this->Staff_request->get_staff_request_by_id($request_id);
        $obj_recruiter = $this->Employer->find($staff_request->recruiter_ID);
    
        $data_email = array(
            'name' => $obj_recruiter->first_name,
            'url_link' => site_url('employer/staff_request/staff_requests/show/' . $request_id),   
            'staff_request' => $staff_request
        );

        if (empty($destinataries)) {
            $to_email = $obj_recruiter->email;
        } else {
            $to_email = $destinataries; 
        }
        
        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($to_email);

        $mail_message = load_email_view('email/gantt_activities_created', $data_email);

        $this->email->subject('Gantt de actividades - Solicitud de personal');
        $this->email->message($mail_message);     

        $this->gantt_request_excel->build_gantt($request_id, $type_gantt);
                
        $this->email->attach(
            $this->gantt_request_excel->getOutput(), 
            'attachment', 
            'Gantt-de-actividades.xlsx', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        return $this->email->send();
    }
}
