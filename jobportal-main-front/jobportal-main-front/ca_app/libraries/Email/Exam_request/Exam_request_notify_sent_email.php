<?php

class Exam_request_notify_sent_email
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->load->library('storage_lib', null, 'Storage_lib');
    }

    public function send(
        $request_id, 
        $emails, 
        $comment
    )
    {
        $this->load->model('Exam_request');
        $this->load->model('Medical_center');

        $exam_request = $this->Exam_request->find($request_id);

        $job = $this->Posted_job->get_posted_job_by_id($exam_request->job_id);

        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);
        
        $document = $this->db->get_where('tbl_exam_request_types', [
            'id' => $exam_request->document_id
        ])->row();
        
        if ($exam_request->medical_center_code == null) {
            return;
        }

        $medical_center = $this->Medical_center->find($exam_request->medical_center_code);

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
            'document' => $document
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
            'exam_request_seekers.file_high_path'
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

        $this->email->subject('COPIA SOLICITUD: ' . 
            $staff_request->client_company_name . ' - Solicitud Examen - ' . $document->description . ' - ' . $medical_center->name
        );
        $this->email->message($mail_message);     
        return $this->email->send();
    }
}
