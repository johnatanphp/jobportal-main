<?php
require_once ("App_console.php");

class Exam_request_notify_seekers extends App_console  
{
    public function __construct()
    {
        parent::__construct();  

        //Load models
        $this->load->model('Exam_request');
        $this->load->model('Medical_center');
        $this->load->model('Exam_document');
    }

    public function run()
    {
        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('active', 1);
        $this->db->where_in('status', [3, 4]);
        $this->db->where('notify_candidate', 1);
        $this->db->where('notification_type', 1);
        
        $this->db->limit(15);

        $result = $this->db->get()->result();

        foreach ($result as $row) {
            
            if (!$row->exam_time) {
                continue;
            }

            $seeker = $this->Job_seeker->get_job_seeker_by_id($row->seeker_id);
            $job = $this->Posted_job->get_posted_job_by_id($row->job_id);
            $medical_center = $this->Medical_center->find($row->medical_center_code);
            $exam_document = $this->Exam_document->find($row->exam_type_id);

            $medical_center_direction = '-';
            $medical_center_location = null;

            if ($row->medical_center_location_id) {

                $medical_center_location = $this->db->get_where('tbl_medical_center_locations', [
                    'id' => $row->medical_center_location_id
                ])->row();

                $medical_center_direction = $medical_center_location->direction;
            }

            $staff_request = $this->Staff_request->find($job->request_ID);
            $employer = $this->Employer->get_employer_by_id($staff_request->employer_ID);
            
            $bcc_emails[] = $employer->email;

            $bcc_emails_extra = explode(',', $this->config->item('exam_request_notification_candidate_bcc_emails'));

            if (count($bcc_emails_extra) > 0) {
                $bcc_emails = array_merge($bcc_emails, $bcc_emails_extra);
            }
            
            $data_email = [
                'seeker' => $seeker,
                'job' => $job,
                'exam_request_seeker' => $row,
                'medical_center' => $medical_center,
                'medical_center_location' => $medical_center_location,
                'exam_document' => $exam_document,
                'medical_center_direction' => $medical_center_direction
            ];

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($seeker->email);
            $this->email->bcc($bcc_emails);
            
            $mail_message = load_email_view(
                'email/exam_request_notify_seeker', 
                $data_email
            );

            $this->email->subject('Cita Programada para ' . $exam_document->name);
            $this->email->message($mail_message); 
            $this->email->attach(FCPATH . 'public/documents/formats/recomendacion-previas-examen-emo.pdf');    
            
            //Send email
            $status_email = $this->email->send();

            $this->load->library('aws_sns_client_lib');

            $sms_text = "Hola " . $seeker->first_name . ", tienes una cita programada de " . $exam_document->name . " para el " .
                    date('d/m/Y', strtotime($row->exam_date)) . " a las " . date('h:i A', strtotime($row->exam_time)) . ", lugar: " . 
                    $medical_center->name  . ($medical_center_location ? ' - ' . $medical_center_location->location : '') . " dirección: "  . $medical_center_direction . " para el puesto " . $job->job_title . ". Portal empleo Overall.";
        
            try {
                $status_sms = $this->aws_sns_client_lib->send_text_message($sms_text, $seeker->mobile);
            } catch (Exception $e)  {}

            $this->db->where('id', $row->id);
            $this->db->update('tbl_exam_request_seekers', [
                'notify_candidate' => 0,
                'candidate_notified' => 1
            ]);
        }
    }
}
