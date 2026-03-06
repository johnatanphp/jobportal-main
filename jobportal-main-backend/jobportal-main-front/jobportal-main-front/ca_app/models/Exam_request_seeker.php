<?php

class Exam_request_seeker extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_exam_request_seekers', $data);
    }

    public function get_scheduled_seekers(
        $job_id, 
        $stage, 
        $exam_type_ids,
        $quote_status = ''
    )
    {
        $query1 = "CREATE TEMPORARY TABLE tbl_recruitment_candidates_exams_tmp ("
                            . "job_id int(11),"
                            . "seeker_id int(11),"
                            . "exam_type_id int(11)"
                            . ");";
 
        $result_table_tmp = $this->db->query($query1);

        if (!$result_table_tmp) {
            return [];
        }

        $result = [];
        
        $filter_exam_type_ids = join(',', $exam_type_ids);

        $this->db->select([
            'seeker_ID AS seeker_id'
        ]);
        $this->db->from('tbl_recruitment_candidates rys_candidates');
        $this->db->where('rys_candidates.job_id', $job_id);
        
        if ($stage != '') {
            $this->db->where('rys_candidates.stage', $stage);
        }

        $results = $this->db->get()->result();

        $rs_data = [];

        foreach ($results as $row) {
            foreach ($exam_type_ids as $exam_type_id) {    
                $rs_data[] = [
                    'job_id' => $job_id,
                    'seeker_id' => $row->seeker_id,
                    'exam_type_id' => $exam_type_id
                ];
            }
        }

        if (count($rs_data) > 0) {
            $this->db->insert_batch('tbl_recruitment_candidates_exams_tmp', $rs_data); 
        }
        
        $this->db->select([
            'id'
        ]);
        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('job_id', $job_id);
        $this->db->where('active', 1);
        $result_ers = $this->db->get()->result();

        $ers_ids[] = -1;
        
        foreach ($result_ers as $row) {
            $ers_ids[] = $row->id;
        }

        $this->db->query('CREATE TEMPORARY TABLE tbl_exam_request_seekers_tmp 
            SELECT * FROM tbl_exam_request_seekers as ers WHERE ers.id IN(' . join(',', $ers_ids) . ');'
        );

        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_type',
            'seekers.document_number',
            'seekers.first_name',
            'seekers.email',
            'seekers.city',
            'exam_request_seekers.id AS exam_seeker_id',
            'exam_request_seekers.exam_date AS exam_request_date',
            'exam_request_seekers.exam_time AS exam_request_time',
            'exam_request_seekers.scheduled_date AS schedule_exam_date',
            'exam_request_seekers.file_high_path',
            'exam_request_seekers.exam_type_id',
            'exam_request_seekers.file_oc_path',
            'exam_request_seekers.ubigeo',
            'exam_request_seekers.comment',
            'exam_request_seekers.active',
            'IFNULL(medical_centers.name, exam_request_seekers.medical_center_name) AS medical_center_name',
            'GROUP_CONCAT(DISTINCT(rce_temp.exam_type_id)) AS exam_type_ids',
            'GROUP_CONCAT(DISTINCT(exam_request_seekers.exam_doc_type)) AS exam_doc_types',
            'GROUP_CONCAT(DISTINCT(exam_request_types.name)) AS exam_type_names',
            'exam_request_seekers.notified_sso',
            'exam_request_seekers.schedule_id',
            'IFNULL(exam_request_seekers.status, 8) AS exam_request_seeker_status',
            'IFNULL(exam_request_status.name, "Sin programar") AS status_name',
            'IFNULL(exam_request_status.color, "#777777") AS status_color' 
        ]);

        $this->db->from('tbl_recruitment_candidates_exams_tmp rce_temp');
        $this->db->join(
            'tbl_recruitment_candidates rys_candidates', 
            'rys_candidates.seeker_ID=rce_temp.seeker_id AND rys_candidates.job_ID=rce_temp.job_id'
        );
        $this->db->join(
            'tbl_job_seekers seekers', 'seekers.ID=rys_candidates.seeker_ID AND rce_temp.seeker_id=seekers.ID'
        );

        $this->db->join('tbl_exam_request_seekers_tmp exam_request_seekers', 
            'exam_request_seekers.seeker_id=rce_temp.seeker_id AND 
            exam_request_seekers.exam_type_id=rce_temp.exam_type_id AND 
            exam_request_seekers.job_id=rce_temp.job_id
            ',
            'left'
        );

        $this->db->join('tbl_exam_request_status exam_request_status', 
            'exam_request_status.id=exam_request_seekers.status',
            'left'
        );

        $this->db->join('tbl_exam_request_types exam_request_types', 
            'exam_request_types.id=rce_temp.exam_type_id',
            'left'
        );

        $this->db->join('tbl_medical_centers medical_centers', 
            'exam_request_seekers.medical_center_code=medical_centers.code',
            'left'
        );

        $this->db->where('rys_candidates.job_id', $job_id);
        
        if ($stage != '') {
            $this->db->where('rys_candidates.stage', $stage);
        }

        $this->db->where_in('rce_temp.exam_type_id', $exam_type_ids);

        if ($quote_status && $quote_status != '8') {
            $this->db->where('exam_request_seekers.status', $quote_status);
        }
      
        if ($quote_status == '8') {
            $this->db->having(
                'exam_request_seekers.status IS NULL OR exam_request_seekers.status=8'
            );
        }
        
        $this->db->group_by([
            'rys_candidates.seeker_ID',
            'exam_request_seekers.scheduled_date',
            'exam_request_seekers.ubigeo',
            'exam_request_seekers.status',
            'exam_request_seekers.notified_sso',
            'exam_request_seekers.medical_center_code',
        ]);

        $this->db->order_by('rys_candidates.seeker_ID', 'ASC');
        $this->db->order_by('exam_request_seekers.active', 'DESC');
         
        return $this->db->get()->result();
    }

    public function get_seekers($request_id)
    {
        $this->db->select([
            'seeker.document_number',
            'seeker.document_type',
            'seeker.first_name',
            'seeker.last_name',
            'seeker.city',
            'request_seeker.id',
            'request_seeker.seeker_id',
            'request_seeker.exam_date',
            'request_seeker.exam_time',
            'request_seeker.ubigeo',
            'request_seeker.comment',
            'request_seeker.medical_center_code',
            'request_seeker.medical_center_location_id',
            'request_seeker.file_high_path',
            'request_seeker.file_oc_path',
            'request_seeker.candidate_notified',
            'medical_centers.name AS medical_center_name',
            'medical_centers.city AS medical_center_city',
            'medical_center_location.location AS medical_center_location',
            'request_seeker.status',
            'request_seeker.active'  
        ]);
        $this->db->from('tbl_exam_request_seekers request_seeker');
        $this->db->join('tbl_job_seekers seeker', 
            'seeker.ID=request_seeker.seeker_id'
        );
        $this->db->join('tbl_medical_centers medical_centers', 
            'medical_centers.code=request_seeker.medical_center_code',
            'left'
        );
        $this->db->join('tbl_medical_center_locations medical_center_location', 
            'medical_center_location.id=request_seeker.medical_center_location_id',
            'left'
        );

        $this->db->where('request_seeker.request_id', $request_id);
        
        return $this->db->get()->result();
    }

    public function get_enabled_seekers($request_id)
    {
        $this->db->select([
            'seeker.document_number',
            'seeker.document_type',
            'seeker.first_name',
            'seeker.last_name',
            'seeker.city',
            'request_seeker.id',
            'request_seeker.seeker_id',
            'request_seeker.exam_date',
            'request_seeker.exam_time',
            'request_seeker.ubigeo',
            'request_seeker.comment',
            'request_seeker.medical_center_code',
            'request_seeker.medical_center_location_id',
            'request_seeker.file_high_path',
            'request_seeker.file_oc_path',
            'medical_centers.name AS medical_center_name',
            'medical_centers.city AS medical_center_city',
            'medical_center_location.location AS medical_center_location',
            'request_seeker.status' 
        ]);
        $this->db->from('tbl_exam_request_seekers request_seeker');
        $this->db->join('tbl_job_seekers seeker', 
            'seeker.ID=request_seeker.seeker_id'
        );
        $this->db->join('tbl_medical_centers medical_centers', 
            'medical_centers.code=request_seeker.medical_center_code',
            'left'
        );
        $this->db->join('tbl_medical_center_locations medical_center_location', 
            'medical_center_location.id=request_seeker.medical_center_location_id',
            'left'
        );

        $this->db->where('request_seeker.request_id', $request_id);
        $this->db->where('request_seeker.active', 1);

        return $this->db->get()->result();
    }

    public function build_data_by_document(
        $staff_request, 
        $exam_type_id
    )
    {
        $this->load->model('Job_profile');
        $this->load->model('Mof');
        $this->load->model('Job_layout');
        
        $data = [];

        if ($exam_type_id == 1 || $exam_type_id == 4) {
            $data = $this->build_data_for_emo($staff_request);
        }

        if ($exam_type_id == 2 || $exam_type_id == 4) {
            $data+= $this->build_data_for_covid19($staff_request);
        }

        if ($exam_type_id == 3) {
            $data = $this->build_data_for_screening($staff_request);
        }

        $data+= $this->build_data_for_exams_complementary($staff_request);

        return $data;
    }

    public function build_data_for_emo($staff_request)
    {
        $data = [];

        $sr_resource = $this->Staff_request->get_resource_by_request_id($staff_request->ID);

        if ($sr_resource) {
            $resource = new stdClass();
            $resource->resource_value = $sr_resource->emo_type_name;

            $data['resource_emo'] = $resource;
        }

        if (!$sr_resource && $staff_request->job_profile_ID) {
            $resource = $this->Job_profile->get_resource_by_name(
                'type_emo', 
                $staff_request->job_profile_ID
            );

            $data['resource_emo'] = $resource;
        }

        if (!$sr_resource && $staff_request->mof_ID) {
            $resource = $this->Mof->get_resource_by_name(
                'type_emo', 
                $staff_request->mof_ID
            );

            $data['resource_emo'] = $resource;
        }

        if (!$sr_resource && $staff_request->job_layout_id) {
            $resource = $this->Job_layout->get_resource_by_name(
                'type_emo', 
                $staff_request->job_layout_id
            );

            $data['resource_emo'] = $resource;
        }

        return $data;
    }

    public function build_data_for_screening($staff_request)
    {
        $data = [];

        $this->load->model('Staff_request');
        $this->load->model('Job_profile');
        $this->load->model('Mof');
        
        $sr_resource = $this->Staff_request->get_resource_by_request_id($staff_request->ID);

        if ($sr_resource) {
            $resource = new stdClass();
            $resource->resource_value = $sr_resource->screening_type_name;

            $data['resource_screening'] = $resource;
        }
        
        if (!$sr_resource && $staff_request->job_profile_ID) {
            $resource = $this->Job_profile->get_resource_by_name(
                'type_screening', 
                $staff_request->job_profile_ID
            );

            $data['resource_screening'] = $resource;
        }

        if (!$sr_resource && $staff_request->mof_ID) {
            $resource = $this->Mof->get_resource_by_name(
                'type_screening', 
                $staff_request->mof_ID
            );

            $data['resource_screening'] = $resource;
        }

        // if (!$sr_resource && $staff_request->job_layout_id) {
        //     $resource = $this->Job_layout->get_resource_by_name(
        //         'type_screening', 
        //         $staff_request->job_layout_id
        //     );

        //     $data['resource_screening'] = $resource;
        // }

        return $data;
    }

    public function build_data_for_covid19($staff_request)
    {
        $data = [];

        $sr_resource = $this->Staff_request->get_resource_by_request_id($staff_request->ID);

        if ($sr_resource) {
            $resource = new stdClass();
            $resource->resource_value = $sr_resource->covid19_type_id;

            $data['resource_covid19'] = $resource;
        }

        if (!$sr_resource && $staff_request->job_profile_ID) {

            $resource = $this->Job_profile->get_resource_by_name(
                'exam_type_covid', 
                $staff_request->job_profile_ID
            );

            $data['resource_covid19'] = $resource;
        }

        if (!$sr_resource && $staff_request->mof_ID) {
            $resource = $this->Mof->get_resource_by_name(
                'exam_type_covid', 
                $staff_request->mof_ID
            );

            $data['resource_covid19'] = $resource;
        }

        if (!$sr_resource && $staff_request->job_layout_id) {
            $resource = $this->Job_layout->get_resource_by_name(
                'exam_type_covid', 
                $staff_request->job_layout_id
            );

            $data['resource_covid19'] = $resource;
        }

        $result_covid19 = [];
        $resource_covid19 = $data['resource_covid19'];

        $covid19_ids = explode(',', $resource_covid19->resource_value);

        if (count($covid19_ids) > 0) {
            $this->db->from('tbl_exam_covid19_types');
            $this->db->where_in('id', $covid19_ids);
            $this->db->where('active', 1);

            $result_covid19 = $this->db->get()->result();
        }

        $data['covid19_options'] = $result_covid19;

        return $data;
    }

    private function build_data_for_exams_complementary($staff_request)
    {
        $data = [];

        if ($staff_request->request_type == 'external') {
            $data['resource_exams_complementary'] = $this->Job_profile->get_resource_by_name(
                'exams_complementary', 
                $staff_request->job_profile_ID
            );
        }

        if ($staff_request->request_type == 'internal') {
            $data['resource_exams_complementary'] = $this->Mof->get_resource_by_name(
                'exams_complementary', 
                $staff_request->mof_ID
            );
        }

        return $data;
    }

    public function find_or_create(
        $job_id, 
        $seeker_id, 
        $exam_type_id
    )
    {        
        $this->db->from('tbl_exam_request_seekers');
        $this->db->where('job_id', $job_id);
        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('active', 1);
        $this->db->where('exam_type_id', $exam_type_id);
        $seeker = $this->db->get()->row();
        
        if ($seeker) {
            return $seeker->id;
        } 

        $this->db->trans_start();

        if (!$seeker) {
            $data = [
                'created_at' => date('Y-m-d H:i:s'),
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'exam_type_id' => $exam_type_id,
                'active' => 1
            ];

            $this->db->insert('tbl_exam_request_seekers', $data);

            $exam_request_seeker_id = $this->db->insert_id();
        }

        $this->db->trans_complete();

        return $this->db->trans_status() ? $exam_request_seeker_id : false;
    }
}
