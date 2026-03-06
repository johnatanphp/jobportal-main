<?php

class Exam_request_notify_medical_center_email
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        //Load models
        $this->load->model('Medical_center_location');
        
        //Load libraries
        $this->load->library('storage_lib', null, 'Storage_lib');
    }

    public function send($request_id, $emails, $comment)
    {
        $this->load->model('Exam_request');
        $this->load->model('Medical_center');

        $exam_request = $this->Exam_request->find($request_id);

        $job = $this->Posted_job->get_posted_job_by_id($exam_request->job_id);

        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);
        
        $document = $this->db->get_where('tbl_exam_request_types', [
            'id' => $exam_request->exam_type_id
        ])->row();
        
        if ($exam_request->medical_center_code == null) {
            return;
        }

        $medical_center = $this->Medical_center->find($exam_request->medical_center_code);

        $medical_center_location = $this->Medical_center_location->find($exam_request->medical_center_location_id);
        
        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);

        $data = [
           'job' => $job,
            'medical_center' => $medical_center,
            'staff_request' => $staff_request,
            'exam_request' => $exam_request,
            'comment' => $comment,
            'document' => $document,
            'exam_date' => $this->get_exam_date($request_id)
        ];

        $mail_message = load_email_view(
            'email/exam_request_to_medical_center',
            $data
        );

        $filters = [
            'request_id' => $request_id
        ];

        $this->load->library(
            'Exports/Exam_request_form_medical_center_export', 
            $filters,
            'Exam_request_form_medical_center_export'
        );

        $this->email->attach(
            $this->Exam_request_form_medical_center_export->output(), 
            'attachment', 
            'SOLICITUD-EXAMEN.xlsx', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $this->db->select([
            'seekers.*',
            'exam_request_seekers.file_high_path',
            'exam_request_seekers.file_oc_path'
        ]);
        $this->db->from('tbl_exam_request_seekers exam_request_seekers');
        $this->db->join('tbl_job_seekers seekers', 
            'seekers.ID=exam_request_seekers.seeker_id'
        );
        
        $this->db->where('exam_request_seekers.request_id', $request_id);
        $this->db->where('(exam_request_seekers.file_high_path IS NOT NULL OR exam_request_seekers.file_oc_path IS NOT NULL)');
        
        $result = $this->db->get()->result();

        foreach ($result as $row) {
            
            $file_value = $this->Storage_lib->get($row->file_high_path);
                        
            if ($file_value !== false) {

                $file_name = @end(explode('/', $row->file_high_path));

                $path =  'public/uploads/tmp/'. $file_name;

                @file_put_contents($path , $file_value);

                $this->email->attach(
                    $path, 
                    'attachment', 
                    $row->document_number . '.' . file_ext($file_name)
                );
            }

            $file_value = $this->Storage_lib->get($row->file_oc_path);
                        
            if ($file_value !== false) {

                $file_name = @end(explode('/', $row->file_oc_path));

                $path =  'public/uploads/tmp/'. $file_name;

                @file_put_contents($path , $file_value);

                $this->email->attach(
                    $path, 
                    'attachment', 
                    'OC-' . $row->document_number . '.' . file_ext($file_name)
                );
            }
        }
            
        $array_subject[] = $this->get_exam_date($request_id);
        $array_subject[] = $document->name;
        $array_subject[] = $staff_request->consultant_name;
        $array_subject[] = $medical_center->name;
        $array_subject[] = $medical_center_location->location;
    
        $this->email->subject(
            join('-', $array_subject)
        );
        $this->email->message($mail_message);     
        return $this->email->send();
    }

    public function get_exam_date($request_id)
    {
        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('request_id', $request_id);
        
        $row = $this->db->get()->row();

        if (!$row || !$row->exam_date) {
            return '';
        }

        return date('d/m/Y', strtotime($row->exam_date));
    }
}
