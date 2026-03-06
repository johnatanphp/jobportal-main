<?php
class Exam_request_schedule extends CI_Model
{       
    public function __construct() {}

    public function find($id)
    {
        $this->db->from('tbl_exam_request_schedules');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function create_schedules($job_id, $exam_type_id)
    {
        $this->db->trans_start();

        $data = [
            'created_at' => date('Y-m-d H:i:s'),
            'job_id' => $job_id,
            'exam_type_id' => $exam_type_id,
            'status' => 1
        ];

        $this->db->insert('tbl_exam_request_schedules', $data);
        $schedule_id = $this->db->insert_id();

        $schedule_update = [
            'schedule_id' => $schedule_id
        ];

        $this->db->where('job_id', $job_id);
        $this->db->where('exam_type_id', $exam_type_id);
        $this->db->where('status', 1);
        $this->db->where('active', 1);
        $this->db->where('schedule_id', null);
        $this->db->update('tbl_exam_request_seekers', $schedule_update);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function search_rys_processes(
        $filter, 
        $per_page = 0, 
        $page = 0
    )
    {
        $query = trim($filter['query']);
        $query_is_digit = ctype_digit($query);

        $this->db->select([
            'exam_request_schedules.id',
            'exam_request_schedules.status',
            'exam_request_schedules.created_at',
            'exam_request_schedules.exam_type_id',
            'job.*',
            'employer_user.first_name AS employer_first_name',
            'employer_user.last_name AS employer_last_name',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'exam_request_types.name AS exam_type_name'
        ]);
        
        $this->db->from('tbl_exam_request_schedules exam_request_schedules');
        $this->db->join('tbl_post_jobs job', 'job.ID=exam_request_schedules.job_id');
        $this->db->join(
            'tbl_exam_request_types exam_request_types', 
            'exam_request_schedules.exam_type_id=exam_request_types.id'
        );

        if ($query_is_digit) {
            $this->db->join(
                'tbl_exam_request_seekers  exam_request_seeker', 
                'exam_request_seeker.schedule_id=exam_request_schedules.id'
            );
            $this->db->join('tbl_job_seekers seeker', 'seeker.ID=exam_request_seeker.seeker_id');
        }
        
        $this->db->join('tbl_staff_requests staff_requests', 'job.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers employer_user', 'staff_requests.employer_ID=employer_user.ID', 'left');

        if (isset($filter['company_id'])) {
           $this->db->where('job.company_ID', $filter['company_id']);
        }

        if (!empty($filter['exam_type_id'])) {
            $this->db->where('exam_request_schedules.exam_type_id', $filter['exam_type_id']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('exam_request_schedules.status', $filter['status']);
        }

        if (!empty($filter['date_start']) && !empty($filter['date_end'])) {
            $this->db->where('exam_request_schedules.created_at>=', $filter['date_start'] . ' 00:00:00');
            $this->db->where('exam_request_schedules.created_at<=', $filter['date_end'] . ' 23:59:59');
        }

        if ($query != '') {
            $this->db->group_start();

            if ($query_is_digit) {
                $this->db->like('seeker.document_number', $query);
                $this->db->or_like('job.ID', $query);
                $this->db->or_like('staff_requests.ID', $query);
            } else {
                $this->db->like('job.job_title', $query);    
                $this->db->or_like('staff_requests.job_title', $query);
            }

            $this->db->group_end();
        }
        
        $this->db->order_by('exam_request_schedules.created_at', 'DESC');

        if ($per_page) {
            $this->db->limit($per_page, ($page > 0 ? $page : 0));
        }

        $this->db->group_by('exam_request_schedules.id');
        
        return $this->db->get()->result();
    }

    public function count_rys_processes(
        $filter
    )
    {
        $query = trim($filter['query']);
        $query_is_digit = ctype_digit($query);

        $this->db->select([
            'exam_request_schedules.id',
        ]);

        $this->db->from('tbl_exam_request_schedules exam_request_schedules');
        $this->db->join('tbl_post_jobs job', 'job.ID=exam_request_schedules.job_id');

        if ($query_is_digit) {
            $this->db->join(
                'tbl_exam_request_seekers  exam_request_seeker', 
                'exam_request_seeker.schedule_id=exam_request_schedules.id'
            );
            $this->db->join('tbl_job_seekers seeker', 'seeker.ID=exam_request_seeker.seeker_id');
        }

        $this->db->join('tbl_staff_requests staff_requests', 'job.request_ID=staff_requests.ID', 'left');
       
        if (isset($filter['company_id'])) {
           $this->db->where('job.company_ID', $filter['company_id']);
        }

        if (!empty($filter['exam_type_id'])) {
            $this->db->where('exam_request_schedules.exam_type_id', $filter['exam_type_id']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('exam_request_schedules.status', $filter['status']);
        }

        if (!empty($filter['date_start']) && !empty($filter['date_end'])) {
            $this->db->where('exam_request_schedules.created_at>=', $filter['date_start'] . ' 00:00:00');
            $this->db->where('exam_request_schedules.created_at<=', $filter['date_end'] . ' 23:59:59');
        }

        $query = trim($filter['query']);

        if ($query != '') {
            $this->db->group_start();
            $query_is_digit = ctype_digit(strval($query));

            if ($query_is_digit) {
                $this->db->like('seeker.document_number', $query);
                $this->db->or_like('job.ID', $query);
                $this->db->or_like('staff_requests.ID', $query);
            } else {
                $this->db->like('job.job_title', $query);    
                $this->db->or_like('staff_requests.job_title', $query);
            }

            $this->db->group_end();
        }

        $this->db->group_by('exam_request_schedules.id');
        
        return $this->db->count_all_results();
    }

    public function search_seekers($schedule_id)
    {
        $this->db->select([
            'seeker.document_number',
            'seeker.document_type',
            'seeker.first_name',
            'seeker.last_name',
            'seeker.city',
            'seeker.ID AS seeker_id',
            'exam_schedule_seekers.id AS scheduled_exam_id',
            'exam_schedule_seekers.scheduled_date AS exam_date',
            'exam_schedule_seekers.ubigeo',
            'exam_schedule_seekers.comment',
            'exam_schedule_seekers.status', 
        ]);
    
        $this->db->from('tbl_exam_request_seekers exam_schedule_seekers');
    
        $this->db->join('tbl_recruitment_candidates rys_seekers', 
            'exam_schedule_seekers.job_id=rys_seekers.job_ID AND rys_seekers.seeker_ID=exam_schedule_seekers.seeker_id'
        );
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=exam_schedule_seekers.seeker_id');

        $this->db->where('exam_schedule_seekers.schedule_id', $schedule_id);
        $this->db->where('rys_seekers.discarded', 0);
        $this->db->where('exam_schedule_seekers.status', 1);
        $this->db->where('exam_schedule_seekers.active', 1);
        
        return $this->db->get()->result();
    }

    public function update_status($schedule_id)
    {
        if (!$schedule_id) {
            return true;
        }

        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('schedule_id', $schedule_id);
        $this->db->where('status', 1);
        $this->db->where('active', 1);

        $count = $this->db->count_all_results();

        if ($count > 0) {
            $this->db->where('id', $schedule_id);
            $this->db->update('tbl_exam_request_schedules', [
                'status' => 1
            ]);

            return true;
        }

        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('schedule_id', $schedule_id);
        $this->db->where('status', 2);
        $this->db->where('active', 1);

        $count = $this->db->count_all_results();

        if ($count > 0) {
            $this->db->where('id', $schedule_id);
            $this->db->update('tbl_exam_request_schedules', [
                'status' => 2
            ]);

            return true;
        }

        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('schedule_id', $schedule_id);
        $this->db->where('status>', 2);
        $this->db->where('active', 1);

        $count = $this->db->count_all_results();

        if ($count > 0) {

            $this->db->where('id', $schedule_id);
            $this->db->update('tbl_exam_request_schedules', [
                'status' => 3
            ]);

            return true;
        }

        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('schedule_id', $schedule_id);
        $this->db->where('active', 0);

        $count = $this->db->count_all_results();

        if ($count > 0) {
            $this->db->where('id', $schedule_id);
            $this->db->update('tbl_exam_request_schedules', [
                'status' => 4
            ]);

            return true;
        }
    }
}
