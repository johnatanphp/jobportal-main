<?php
require_once ("App_console.php");

class Create_schedule_exams extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        //Fecha actual
        $date_now = date('Y-m-d');
        
        //Fecha Ayer
        $yesterday = date('Y-m-d', strtotime($date_now . ' -1 day')); 

        //Fecha Mañana
        $tomorrow = date('Y-m-d', strtotime($date_now . ' +1 day')); 
       
        $this->db->select([
            's.ID AS seeker_id',    
            's.first_name AS seeker_first_name',
            's.last_name AS seeker_last_name',
            's.document_number AS seeker_document_number',
            'rc.job_ID AS job_id',
            's.email',
            's.city',
            's.dob',
            's.mobile',
            's.present_address'
        ]);
        $this->db->from('tbl_recruitment_log_candidate_stage rc_logs');
        $this->db->join('tbl_recruitment_candidates rc', 'rc.job_ID=rc_logs.job_ID AND rc.seeker_ID=rc_logs.seeker_ID AND rc.stage=rc_logs.stage');
        $this->db->join('tbl_post_jobs jobs', 'rc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests r', 'r.ID=jobs.request_ID');
        $this->db->join('tbl_job_seekers s', 's.ID=rc.seeker_ID');
        $this->db->where('rc.discarded', 0);
        $this->db->where('rc.contracted', 0);
        $this->db->where('r.request_model_id', 3); //Modelo 3 MK
        $this->db->where('r.company_ID', 1); //Overall
        // $this->db->where('rc_logs.datetime>=', $yesterday . ' 15:00:00');
        // $this->db->where('rc_logs.datetime<=', $date_now . ' 15:00:00');
        
        $results = $this->db->get()->result();

        //desde fecha dia anterior >= 3
        //hasta fech hoy 3

        $created_at = date('Y-m-d Y:i:s');

        foreach ($results AS $row_seeker) {

            $this->db->from('tbl_exam_request_seekers');
            $this->db->where('exam_type_id', 1); //EMPO
            $this->db->where('seeker_id', $row_seeker->seeker_id);
            $this->db->where('active', 1);
            $this->db->where('status!=', 5);
            $exam_request = $this->db->get()->row();

            if ($exam_request) {
                continue;
            }

            $data_er_seeker = [
                'created_at' => $created_at,
                'exam_type_id' => 1, //EMPO
                'exam_doc_type' => '',
                'type_expense' => '',
                'scheduled_date' => $tomorrow,
                'job_id' => $row_seeker->job_id,
                'seeker_id' => $row_seeker->seeker_id,
                'ubigeo' => $row_seeker->city,
                'active' => 1,
                'status' => 1
            ];

            $this->db->insert('tbl_exam_request_seekers', $data_er_seeker);
        }

        dd($results);
    }
}
