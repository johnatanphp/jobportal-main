<?php

class Exam_request_cancel_seeker_email
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        //Load models
        $this->load->model('Exam_request');
        $this->load->model('Exam_request_seeker');
        $this->load->model('Exam_document');
        $this->load->model('Medical_center');
        $this->load->model('Medical_center_location');
        
        //Load libraries
        $this->load->library('aws_sns_client_lib');
    }

    public function send( 
        $exam_seeker_id
    )
    {
        $exam_request_seeker = $this->Exam_request_seeker->find($exam_seeker_id);

        if ($exam_request_seeker->status != 3 && $exam_request_seeker->status != 4) {
            return;
        }

        $seeker = $this->Job_seeker->get_job_seeker_by_id($exam_request_seeker->seeker_id);

        $job = $this->Posted_job->get_posted_job_by_id($exam_request_seeker->job_id);

        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);
            
        $document = $this->Exam_document->find($exam_request_seeker->exam_type_id);

        $medical_center = $this->Medical_center->find($exam_request_seeker->medical_center_code);

        $medical_center_location = $this->Medical_center_location->find($exam_request_seeker->medical_center_location_id);
        
        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($seeker->email);

        $data = [
            'job' => $job,
            'staff_request' => $staff_request,
            'exam_request_seeker' => $exam_request_seeker,
            'seeker' => $seeker,
            'document' => $document,
        ];

        $mail_message = load_email_view(
            'email/exam_requests/notify_cancel_seeker',
            $data
        );

        $this->email->subject('CITA CANCELADA - ' . $document->name);
        $this->email->message($mail_message);     
        $this->email->send();
        
        //Enviar mensaje de notificación
        $sms_text = "Hola " . $seeker->first_name . ", tu cita de " . $document->name . " se ha cancelado. Portal empleo Overall.";

        $this->aws_sns_client_lib->send_text_message($sms_text, $seeker->mobile);
    }
}
