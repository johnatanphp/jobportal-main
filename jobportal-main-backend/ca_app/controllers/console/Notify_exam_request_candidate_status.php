<?php
require_once ("App_console.php");

class Notify_exam_request_candidate_status extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'requests.ID AS request_id',
            'requests.job_title AS request_job_title',
            'jobs.ID AS job_id',
            'count(seekers.ID) AS candidate_total',
            'recruiters.email AS recruiter_email',
            'employers.email AS employer_email'
        ]);
        $this->db->from('tbl_exam_request_seekers er_seekers');
        $this->db->join('tbl_job_seekers seekers', 'er_seekers.seeker_id=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=er_seekers.job_id');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=jobs.request_ID');
        $this->db->join('tbl_employers recruiters', 'requests.recruiter_ID=recruiters.ID');
        $this->db->join('tbl_employers employers', 'requests.employer_ID=employers.ID');

        $this->db->where('er_seekers.created_at>=', '2025-02-21 00:00:00');
        $this->db->where('er_seekers.notify_recruiter', 1);

        $this->db->group_by('requests.ID');
        $this->db->limit(12);

        $result = $this->db->get()->result();

        //dd($result);
        
        $this->load->library('Exports/Exam_request_candidates_status_export');

        foreach ($result as $seeker) {

            $request_id =  $seeker->request_id;

            $this->exam_request_candidates_status_export->build([
                'request_id' => $request_id
            ]);

            $file_excel_path = $this->exam_request_candidates_status_export->save(sys_get_temp_dir() . '/' . md5(uniqid($request_id, true)) . '.xlsx');

            //dd($file_excel_path);

            if ($file_excel_path === false) {
                continue;
            }

            $data_email = [
                'exam_candidate' => $seeker  
            ];
            
            $emails = [];
            $emails[] = $seeker->employer_email;
            $emails[] = $seeker->recruiter_email;
            
            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($emails);
            $this->email->attach($file_excel_path, 'attachment', 'empo-listado-postulantes.xlsx');

            $mail_message = load_email_view('email/exam_requests/notify_candidate_status', $data_email);
    
            $this->email->subject('Actualización de Estatus Exámenes EMPO - ' . $seeker->request_id . '-' . trim($seeker->request_job_title));
            $this->email->message($mail_message); 
            $email_status = $this->email->send(); 

            if ($email_status) {
                $this->db->where('job_id', $seeker->job_id);
                $this->db->where('notify_recruiter', 1);
                $this->db->update('tbl_exam_request_seekers', [
                    'notify_recruiter' => 2
                ]);
            }
        }
    }
}
