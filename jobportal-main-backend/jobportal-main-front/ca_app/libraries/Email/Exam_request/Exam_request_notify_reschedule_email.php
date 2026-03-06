<?php

class Exam_request_notify_reschedule_email
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($request_id)
    {
        $exam_request = $this->Exam_request->find($request_id);

        $job = $this->Posted_job->get_posted_job_by_id($exam_request->job_id);

        $staff_request = $this->Staff_request->find($job->request_ID);
        $employer = $this->Employer->get_employer_by_id($staff_request->employer_ID);
        $emails[] = $employer->email;

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);

        $enlace = site_url('employer/recruitment_processes/show_process/' . $exam_request->job_id);

        $mail_message = "Se ha reprogramado cita(s) a candidatos para la solucitud de examen, por favor te invitamos a revisar el proceso <b>RYS Id: " . $exam_request->job_id . " - " . $job->job_title . "</b> para mas detalle.<br /><br /> <a href='" . $enlace . "'> Ir al proceso</a>" ;

        $this->email->subject('Reprogramación de citas - Solicitud Examen');
        $this->email->message($mail_message);     
        //Send email
        $this->email->send();
    }
}
