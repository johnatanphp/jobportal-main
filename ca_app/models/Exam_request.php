<?php

class Exam_request extends CI_Model
{
    //protected $table = 'tbl_exam_requests';
    //protected $primary_key = 'request_id';

    public function find($id)
    {
        $this->db->from('tbl_exam_requests');
        $this->db->where('request_id', $id);

        return $this->db->get()->row();
    }

    public function add($data)
    {
        $return = $this->db->insert('tbl_exam_requests', $data);

        if ((bool) $return === TRUE) {
            return $this->db->insert_id();
        } else {
            return $return;
        } 
    }

    public function update($id, $data)
    {
        $this->db->where('request_id', $id);
        return $this->db->update('tbl_exam_requests', $data);
    }

    public function search_requests_by_schedule_id(
        $schedule_id,
        $filters
    )
    {
        $sql = "SELECT COUNT(s.id) FROM tbl_exam_request_seekers AS s WHERE s.candidate_notified = 0 AND s.request_id=doc_requests.request_id";

        $this->db->select([
            'doc_requests.request_id',
            'doc_requests.creation_date',
            'doc_requests.status',
            'doc_requests.oc_status',
            'doc_requests.oc_id',
            'doc_types.name AS document_name',
            'medical_center.name AS medical_center_name',
            'medical_center_locations.location AS medical_center_location',
            '(' . $sql . ') AS candidates_to_notify'
        ]);
        
        $this->db->from('tbl_exam_requests doc_requests');
        $this->db->join('tbl_exam_request_types doc_types', 
            'doc_types.id=doc_requests.exam_type_id'
        );
        $this->db->join(
            'tbl_medical_centers medical_center', 
            'medical_center.code=doc_requests.medical_center_code',
            'left'
        );
        $this->db->join(
            'tbl_medical_center_locations medical_center_locations', 
            'medical_center_locations.id=doc_requests.medical_center_location_id',
            'left'
        );

        $this->db->where('doc_requests.schedule_id', $schedule_id);

        if ($filters['exam_type_id'] != '') {
            $this->db->where('doc_requests.exam_type_id', $filters['exam_type_id']);
        }

        if ($filters['medical_center'] != '') {
            $this->db->where('doc_requests.medical_center_code', $filters['medical_center']);
        }

        if ($filters['status'] != '') {
            $this->db->where('doc_requests.status', $filters['status']);
        }

        if ($filters['status_oc'] != '') {
            $this->db->where('doc_requests.oc_status', $filters['status_oc']);
        }
        
        $this->db->order_by('doc_requests.creation_date', 'DESC');
        $this->db->order_by('doc_requests.status', 'ASC');

        $this->db->group_by('doc_requests.request_id');

        return $this->db->get()->result();
    }

    public function search_requests_by_job_id(
        $job_id,
        $filters
    )
    {
        $sql = "SELECT COUNT(s.id) FROM tbl_exam_request_seekers AS s WHERE s.candidate_notified = 0 AND s.request_id=doc_requests.request_id";

        $this->db->select([
            'doc_requests.request_id',
            'doc_requests.creation_date',
            'doc_requests.status',
            'doc_requests.oc_status',
            'doc_requests.oc_id',
            'doc_types.name AS document_name',
            'medical_center.name AS medical_center_name',
            'medical_center_locations.location AS medical_center_location',
            '(' . $sql . ') AS candidates_to_notify'
        ]);
        
        $this->db->from('tbl_exam_requests doc_requests');
        $this->db->join('tbl_exam_request_types doc_types', 'doc_types.id=doc_requests.exam_type_id');
        $this->db->join(
            'tbl_medical_centers medical_center', 
            'medical_center.code=doc_requests.medical_center_code',
            'left'
        );
        $this->db->join(
            'tbl_medical_center_locations medical_center_locations', 
            'medical_center_locations.id=doc_requests.medical_center_location_id',
            'left'
        );

        $this->db->where('doc_requests.job_id', $job_id);

        if ($filters['exam_type_id'] != '') {
            $this->db->where('doc_requests.exam_type_id', $filters['exam_type_id']);
        }

        if ($filters['medical_center'] != '') {
            $this->db->where('doc_requests.medical_center_code', $filters['medical_center']);
        }

        if ($filters['status'] != '') {
            $this->db->where('doc_requests.status', $filters['status']);
        }

        if ($filters['status_oc'] != '') {
            $this->db->where('doc_requests.oc_status', $filters['status_oc']);
        }
        
        $this->db->order_by('doc_requests.creation_date', 'DESC');
        $this->db->order_by('doc_requests.status', 'ASC');

        $this->db->group_by('doc_requests.request_id');

        return $this->db->get()->result();
    }

    public function create_and_assign_group_by_medical_center(
        $schedule_id,
        $job_id, 
        $exam_type_id,
        $mc_codes = [],
        $mc_location_ids = [],
        $edit_request_id = null
    )
    {
        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('schedule_id', $schedule_id);
        //$this->db->where('job_id', $job_id);
        //$this->db->where('exam_type_id', $exam_type_id);

        $this->db->where('status', 2);
        $this->db->where('active', 1);
        $this->db->where_in('medical_center_code', $mc_codes);
        $this->db->where_in('medical_center_location_id', $mc_location_ids);
        
        $this->db->group_by(['medical_center_code', 'medical_center_location_id']);

        $result = $this->db->get()->result();
        
        foreach ($result as $row) {

            $exam_request_data = [
                'schedule_id' => $schedule_id,
                'job_id' => $job_id,
                'exam_type_id' => $exam_type_id,
                'creation_date' => date('Y-m-d H:i:s'),
                'medical_center_code' => $row->medical_center_code,
                'medical_center_location_id' => $row->medical_center_location_id,
                'user_id' => $this->session->userdata('user_id')
            ];

            $exam_request_id = $this->add($exam_request_data);

            //$this->db->where('job_id', $job_id);
            //$this->db->where('exam_type_id', $exam_type_id);
            $this->db->where('schedule_id', $schedule_id);
            $this->db->where('medical_center_code', $row->medical_center_code);
            $this->db->where('medical_center_location_id', $row->medical_center_location_id);
            $this->db->where('status', 2);
            $this->db->where('active', 1);

            $this->db->update('tbl_exam_request_seekers', [
                'request_id' => $exam_request_id,
            ]);
        }

        $this->delete_exam_request_with_seekers($schedule_id);
    }

    public function delete_exam_request_with_seekers($schedule_id = 0)
    {
        $this->db->select([
            'requests.request_id',
            'COUNT(request_seeker.request_id) AS count_request_seekers'
        ]);
        $this->db->from('tbl_exam_requests requests');
        $this->db->join('tbl_exam_request_seekers  request_seeker', 
            'requests.request_id=request_seeker.request_id', 
            'left'
        );

        $this->db->where('requests.schedule_id', $schedule_id);
        //$this->where('requests.job_id', $job_id);
        //$this->where('requests.exam_type_id', $exam_type_id);

        $this->db->having('count_request_seekers', 0);

        $this->db->group_by('requests.request_id');

        $result = $this->db->get()->result();
    
        foreach ($result as $key => $row) {
            $this->db->where('request_id', $row->request_id);
            $this->db->where('oc_status', 1);
            $this->db->delete('tbl_exam_requests');
        }
    }

    public function get_oc_ids_created($id)
    {
        return [];
    }
}
