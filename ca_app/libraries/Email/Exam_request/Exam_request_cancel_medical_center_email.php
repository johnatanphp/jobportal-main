<?php

class Exam_request_cancel_medical_center_email
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
        $this->load->model('Medical_center_email');
    }

    public function send( 
        $exam_seeker_id
    )
    {
        $exam_request_seeker = $this->Exam_request_seeker->find($exam_seeker_id);

        $exam_request = $this->Exam_request->find($exam_request_seeker->request_id);

        if ($exam_request_seeker->status != 3 && $exam_request_seeker->status != 4) {
            return;
        }

        $seeker = $this->Job_seeker->get_job_seeker_by_id($exam_request_seeker->seeker_id);

        $job = $this->Posted_job->get_posted_job_by_id($exam_request_seeker->job_id);

        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        $employer = $this->Employer->get_employer_by_id($staff_request->employer_ID);
            
        $document = $this->Exam_document->find($exam_request_seeker->exam_type_id);

        $medical_center = $this->Medical_center->find($exam_request_seeker->medical_center_code);
        
        $request_copy_emails = [];

        if (!empty($exam_request->send_copy_emails)) {
            $request_copy_emails = explode(',', $exam_request->send_copy_emails);
        }

        $medical_center_emails = $this->Medical_center_email->get_emails_by_code($medical_center->code);

        $emails = array_merge($medical_center_emails, $request_copy_emails);

        $bcc_emails[] = $employer->email;

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);
        $this->email->bcc($bcc_emails);

        $data = [
            'job' => $job,
            'staff_request' => $staff_request,
            'exam_request_seeker' => $exam_request_seeker,
            'seeker' => $seeker,
            'document' => $document,
        ];

        $mail_message = load_email_view(
            'email/exam_requests/notify_cancel_medical_center',
            $data
        );

        $subject = 'CITA CANCELADA - ' . 
                    $document->name . ' - ' .
                    date('d/m/Y', strtotime($exam_request_seeker->exam_date)) . ' - ' . 
                    $staff_request->consultant_name . ' - ' . 
                    $medical_center->name;

        $this->email->subject($subject);
        $this->email->message($mail_message); 

        $parameters = [
            'exam_type' => $exam_request_seeker->exam_type_id,
            'request_seeker_id' => $exam_request_seeker->id,
            'export_type' => 2 //Cancelación
        ];
        $this->load->library(
            'Exports/Exam_request_seekers_export', 
            $parameters,
            'Exam_request_seekers_export'
        );

        $this->email->attach(
            $this->Exam_request_seekers_export->output(), 
            'attachment', 
            'CANCELACIÓN-SOLICITUD-EXAMEN.xlsx', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $this->email->send();
    }
}
