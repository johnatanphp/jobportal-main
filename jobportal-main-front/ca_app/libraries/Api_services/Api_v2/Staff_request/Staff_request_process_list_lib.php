<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_request_process_list_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($input_data)
    {
        $gender_list = [
            'male' => 'Hombre',
            'female' => 'Mujer',
            'both' => 'Ambos'
        ];

        $process_data =  [];

        $sr_process_results = $this->get_sr_process($input_data);

        $sr_process_ids = $this->build_array_process_ids($sr_process_results);
        $data_working_days = $this->get_working_days($sr_process_ids);
        $data_commercial_premises = $this->get_commercial_premises($sr_process_ids);
        $data_job_experiences = $this->get_job_experiences($sr_process_ids);
        $data_seeker_skills = $this->get_seeker_skills($sr_process_ids);

        foreach ($sr_process_results as $process_row) {

            $recruiter = null;

            if ($process_row->recruiter_id) {
                $recruiter = [
                    'id' => (int)$process_row->recruiter_id,
                    'first_name' => $process_row->recruiter_first_name,
                    'last_name' => $process_row->recruiter_last_name
                ];
            }

            $job_layout = [
                'id' => (int)$process_row->job_layout_id,
                'job_title' => $process_row->job_layout_job_title,
            ];

            $request = [
                'id' => (int)$process_row->request_id
            ];

            $working_days = [];

            foreach (($data_working_days[$process_row->id] ?? []) as $row) {
                $working_days[] = (int)$row->day;
            }

            $commercial_premises = [];

            foreach (($data_commercial_premises[$process_row->id] ?? []) as $row) {
                $commercial_premises[] = [
                    'id' => (int)$row->id,
                    'name' => $row->name
                ];
            }

            $job_experiences = [];

            foreach (($data_job_experiences[$process_row->id] ?? []) as $row) {
                $job_experiences[] = [
                    'id' => (int)$row->id,
                    'name' => $row->name
                ];
            }

            $seeker_skills = [];

            foreach (($data_seeker_skills[$process_row->id] ?? []) as $row) {
                $seeker_skills[] = [
                    'id' => (int)$row->id,
                    'name' => $row->name
                ];
            }

            $process_data[] = [
                'id' => (int)$process_row->id,
                'job_title' => $process_row->job_title,
                'vacancies' => (int)$process_row->vacancies,
                'type_reason_id' => $process_row->type_reason_id,
                'type_reason_name' => $process_row->type_reason_name,
                'start_date_work' => $process_row->start_date_work ? $process_row->start_date_work : null,
                'end_date_work' => $process_row->end_date_work ? $process_row->end_date_work : null,
                'start_hour_work' => $process_row->start_hour_work ? $process_row->start_hour_work : null,
                'end_hour_work' => $process_row->end_hour_work ? $process_row->end_hour_work : null,
                'gender_id' => $process_row->gender,
                'gender_name' => $gender_list[$process_row->gender] ?? null,
                'campaign_id' => $process_row->campaign_id ? (int)$process_row->campaign_id : null,
                'campaign_name' => $process_row->campaign_name,
                'location_type_id' => (int)$process_row->location_type_id,
                'location_type_name' => $process_row->location_type_name,
                'job_worker_education_id' => (int)$process_row->education_id,
                'job_worker_education_name' => $process_row->education_name,
                'job_location_department_id' => $process_row->job_loc_departament_id,
                'job_location_department_name' => $process_row->job_loc_departament_name,
                'job_location_province_id' => $process_row->job_loc_province_id,
                'job_location_province_name' => $process_row->job_loc_province_name,
                'job_location_district_id' => $process_row->job_loc_district_id,
                'job_location_district_name' => $process_row->job_loc_district_name,
                'job_worker_experience_months' => (int)$process_row->job_worker_experience_months,
                'job_worker_experience_channel' => (int)$process_row->job_worker_experience_channel,
                'job_worker_experience_client' => (int)$process_row->job_worker_national,
                'job_worker_national' => (int)$process_row->job_worker_national,
                'job_worker_health_carnet' => (int)$process_row->job_worker_health_carnet,
                'job_worker_health_insurance' => (int)$process_row->job_worker_health_insurance,
                'job_worker_health_disabilities' => (int)$process_row->job_worker_health_disabilities,
                'candidates' => 0,
                'candidates_hired' => 0,
                'request' => $request,
                'recruiter' => $recruiter,
                'job_layout' => $job_layout,
                'job_working_days' => $working_days,
                'job_location_locals' => $commercial_premises,
                'job_worker_skills' => $seeker_skills,
                'job_worker_experiences' => $job_experiences,
            ];   
        }

        return [
            'status' => true,
            'message' => 'OK',
            'data' => $process_data
        ];
    }

    private function get_sr_process($input_data)
    {
        $this->db->select([
            'sr_header.id AS request_id',
            'sr_process.ID AS id',
            'sr_process.job_title AS job_title',
            'sr_process.vacancies AS vacancies',
            'sr_process.request_header_id',
            'sr_process.reason_request AS type_reason_id',
            'type_reasons.name AS type_reason_name',
            'sr_process.start_date_work',
            'sr_process.end_date_work',
            'sr_process.start_hour_work',
            'sr_process.end_hour_work',
            'sr_process.gender',
            'sr_process.campaign_id AS campaign_id',
            'campaigns.name AS campaign_name',
            'sr_process.job_location_type_id AS location_type_id',
            'location_types.name AS location_type_name',
            'sr_process.job_worker_education_id AS education_id',
            'educations.text AS education_name',
            'sr_process.job_layout_id AS job_layout_id',
            'job_layouts.job_title AS job_layout_job_title',
            'sr_process.job_location_department_id AS job_loc_departament_id',
            'departament.order_administrative1 AS job_loc_departament_name',
            'sr_process.job_location_province_id AS job_loc_province_id',
            'province.order_administrative2 AS job_loc_province_name',
            'sr_process.job_location_district_id AS job_loc_district_id',
            'district.order_administrative3 AS job_loc_district_name',
            'sr_process.job_worker_experience_months',
            'sr_process.job_worker_experience_channel',
            'sr_process.job_worker_experience_client',
            'sr_process.job_worker_national',
            'sr_process.job_worker_health_carnet',
            'sr_process.job_worker_health_insurance',
            'sr_process.job_worker_health_disabilities',
            'recruiter.ID AS recruiter_id',
            'recruiter.first_name AS recruiter_first_name',
            'recruiter.last_name AS recruiter_last_name'
        ]);
        
        $this->db->from('tbl_staff_request_headers sr_header');
        $this->db->join('tbl_staff_requests sr_process', 'sr_header.id=sr_process.request_header_id');
        $this->db->join('tbl_job_layouts job_layouts', 'job_layouts.id=sr_process.job_layout_id');
        $this->db->join('tbl_staff_request_type_reasons type_reasons', 'sr_process.reason_request=type_reasons.id', 'left');
        $this->db->join('tbl_campaigns campaigns', 'campaigns.id=sr_process.campaign_id', 'left');
        $this->db->join('tbl_staff_request_job_location_types location_types', 'location_types.id=sr_process.job_location_type_id', 'left');
        $this->db->join('tbl_qualifications educations', 'educations.ID=sr_process.job_worker_education_id', 'left');
        $this->db->join('tbl_employers recruiter', 'recruiter.ID=sr_process.employer_ID', 'left');
        $this->db->join('tbl_ubigeos departament', 'departament.order_administrative1_code=sr_process.job_location_department_id AND departament.country_id=56', 'left');
        $this->db->join('tbl_ubigeos province', 'province.order_administrative2_code=sr_process.job_location_province_id AND province.country_id=56', 'left');
        $this->db->join('tbl_ubigeos district', 'district.order_administrative3_code=sr_process.job_location_district_id AND district.country_id=56', 'left');

        if (isset($input_data['request_id'])) {
            $this->db->where('sr_header.id', trim($input_data['request_id']));
        }

        if (isset($input_data['request_user_id'])) {
            $this->db->where('sr_header.request_user_id', trim($input_data['request_user_id']));
        }

        if (isset($input_data['request_consulant_id'])) {
            $this->db->where('sr_header.no_cia', trim($input_data['request_consulant_id']));
        }

        if (isset($input_data['request_client_id'])) {
            $this->db->where('sr_header.cod_clie', trim($input_data['request_client_id']));
        }

        if (isset($input_data['request_status'])) {
            $this->db->where('sr_header.request_status', trim($input_data['request_status']));
        } 
        
        if (isset($input_data['process_id'])) {
            $this->db->where('sr_process.ID', trim($input_data['process_id']));
        } 

        if (isset($input_data['process_job_layout_id'])) {
            $this->db->where('sr_process.job_layout_id', trim($input_data['process_job_layout_id']));
        } 

        if (isset($input_data['process_type_reason_id'])) {
            $this->db->where('sr_process.reason_request', trim($input_data['process_type_reason_id']));
        } 
        
        $this->db->group_by('sr_process.ID');

        return $this->db->get()->result();
    }

    private function build_array_process_ids($result_process)
    {   
        $ids = [];

        foreach ($result_process as $row) {
            $ids[$row->id] = $row->id;
        }

        return $ids;
    }

    private function get_working_days($process_ids)
    {
        if (count($process_ids) == 0) {
            return [];
        }

        $this->db->from('tbl_staff_request_working_days');
        $this->db->where_in('request_id', $process_ids);
        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[$row->request_id][] = $row;
        }

        return $data;
    }

    private function get_commercial_premises($process_ids)
    {
        if (count($process_ids) == 0) {
            return [];
        }
        
        $this->db->select([
            'cp.id AS id',
            'cp.name AS name',
            'sr_cp.request_id'
        ]);
        $this->db->from('tbl_staff_request_commercial_premises sr_cp');
        $this->db->join('tbl_commercial_premises cp', 'cp.id=sr_cp.commercial_premise_id');
        $this->db->where_in('sr_cp.request_id', $process_ids);
        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[$row->request_id][] = $row;
        }

        return $data;
    }

    private function get_job_experiences($process_ids)
    {
        if (count($process_ids) == 0) {
            return [];
        }

        $this->db->select([
            'je.id AS id',
            'je.name AS name',
            'sr_je.request_id'
        ]);
        $this->db->from('tbl_staff_request_job_experiences sr_je');
        $this->db->join('tbl_job_experiences je', 'je.id=sr_je.experience_id');
        $this->db->where_in('sr_je.request_id', $process_ids);
        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[$row->request_id][] = $row;
        }

        return $data;
    }

    private function get_seeker_skills($process_ids)
    {
        if (count($process_ids) == 0) {
            return [];
        }

        $this->db->select([
            'skill.ID AS id',
            'skill.skill_name AS name',
            'sr_skill.request_id'
        ]);
        $this->db->from('tbl_staff_request_seeker_skills sr_skill');
        $this->db->join('tbl_skills skill', 'skill.ID=sr_skill.skill_id');
        $this->db->where_in('sr_skill.request_id', $process_ids);
        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[$row->request_id][] = $row;
        }

        return $data;
    }
}
