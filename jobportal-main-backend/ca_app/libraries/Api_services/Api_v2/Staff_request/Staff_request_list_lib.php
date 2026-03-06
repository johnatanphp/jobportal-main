<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_request_list_lib
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

        $result_data = [];

        $result_requests = $this->get_result_requests($input_data);

        $sr_ids = [];

        foreach ($result_requests as $row) {
            $sr_ids[] = $row->id;
        }

        $data_job_proceses = $this->get_sr_proceses($sr_ids);
        // $data_working_days = $this->get_working_days($sr_ids);
        // $data_job_experiences = $this->get_job_experiences($sr_ids);
        // $data_seeker_skills = $this->get_seeker_skills($sr_ids);
    
        foreach ($result_requests as $sr_row) {

            $process_data = [];

            $recruiter = null;

            $job_layout = [
                'id' => (string)$sr_row->job_layout_id,
                'job_title' => (string)$sr_row->job_layout_job_title,
            ];

            if ($sr_row->recruiter_id) {
                $recruiter = [
                    'id' => (string)$sr_row->recruiter_id,
                    'first_name' => (string)$sr_row->recruiter_first_name,
                    'last_name' => (string)$sr_row->recruiter_last_name
                ];
            }

            $result_data[] = [
                'id' => (string)$sr_row->id,
                'name' => $sr_row->job_title,
                'created_at' => $sr_row->creation_date,
                'status_id' => (string)$sr_row->status_id,
                'status_name' => $sr_row->status_name,
                'consultant_id' => $sr_row->consultant_id,
                'consultant_name' => $sr_row->consultant_name,
                'client_id' => $sr_row->client_id,
                'client_name' => $sr_row->client_name,
                'business_unit_id' => $sr_row->business_unit_id,
                'business_unit_name' => $sr_row->business_unit_name,
                'cost_center_id' => $sr_row->cost_center_id,
                'expense_type_id' => (string)$sr_row->expense_type_id,
                'type_reason_id' => $sr_row->type_reason_id,
                'type_reason_name' => $sr_row->type_reason_name,
                'start_date_work' => $sr_row->start_date_work ? $sr_row->start_date_work : null,
                'end_date_work' => $sr_row->end_date_work ? $sr_row->end_date_work : null,
                'start_hour_work' => $sr_row->start_hour_work ? $sr_row->start_hour_work : null,
                'end_hour_work' => $sr_row->end_hour_work ? $sr_row->end_hour_work : null,
                'gender_id' => $sr_row->gender,
                'gender_name' => $gender_list[$sr_row->gender] ?? null,
                'date_due' => $sr_row->date_due,
                'campaign_id' => $sr_row->campaign_id ? (string)$sr_row->campaign_id : null,
                'campaign_name' => $sr_row->campaign_name,
                'location_type_id' => (string)$sr_row->location_type_id,
                'location_type_name' => $sr_row->location_type_name,
                'job_list_type' => (string)$sr_row->job_list_type,
                'job_description' => (string)$sr_row->job_description,
                'stage_group_id' => (string)$sr_row->stage_group_id,
                'screening_phase_id' => (string)$sr_row->screening_phase_id,
                'job_url' => $sr_row->job_slug ? site_url('jobs/' . $sr_row->job_slug) : '',
                'job_layout' => $job_layout,
                // 'job_worker_education_id' => (int)$sr_row->education_id,
                // 'job_worker_education_name' => $sr_row->education_name,
                // 'job_working_days' => $this->build_data_request_working_days($data_working_days[$sr_row->id] ?? []),
                // 'job_worker_skills' => $this->build_data_request_skills($data_seeker_skills[$sr_row->id] ?? []),
                // 'job_worker_experiences' => $this->build_data_request_job_experiences($data_job_experiences[$sr_row->id] ?? []),
                // 'job_worker_experience_months' => (int)$sr_row->job_worker_experience_months,
                // 'job_worker_experience_channel' => (int)$sr_row->job_worker_experience_channel,
                // 'job_worker_experience_client' => (int)$sr_row->job_worker_national,
                // 'job_worker_national' => (int)$sr_row->job_worker_national,
                // 'job_worker_health_carnet' => (int)$sr_row->job_worker_health_carnet,
                // 'job_worker_health_insurance' => (int)$sr_row->job_worker_health_insurance,
                // 'job_worker_health_disabilities' => (int)$sr_row->job_worker_health_disabilities,
                'processes' => $this->build_data_rs_proceses($data_job_proceses[$sr_row->id] ?? []),
                'created_by' => $recruiter,
                'has_penalty' => (int)$sr_row->has_penalty
            ];
        }

        return apiv2_response(true, 'OK', $result_data);    
    }

    private function get_result_requests($input_data)
    {   
        $employer_id = $this->session_employer_lib->get_data('user_id');
        $employer = $this->Employer->find($employer_id);

        $this->db->select([
            'sr_process.ID AS id',
            'sr_process.creation_date AS creation_date',
            'sr_process.job_title AS job_title',
            'sr_process.no_cia AS consultant_id',
            'sr_process.consultant_name AS consultant_name',
            'sr_process.cod_clie AS client_id',
            'sr_process.client_company_name AS client_name',
            'sr_process.cod_business_unit AS business_unit_id',
            'sr_process.business_unit_name AS business_unit_name',
            'sr_process.cost_center AS cost_center_id',
            'sr_process.type_expense AS expense_type_id',
            'sr_process.vacancies AS vacancies',
            'sr_process.reason_request AS type_reason_id',
            'type_reasons.name AS type_reason_name',
            'sr_process.start_date_work',
            'sr_process.end_date_work',
            'sr_process.start_hour_work',
            'sr_process.end_hour_work',
            'sr_process.gender',
            'sr_process.delivery_date AS date_due',
            'sr_process.campaign_id AS campaign_id',
            'campaigns.name AS campaign_name',
            'sr_process.job_location_type_id AS location_type_id',
            'location_types.name AS location_type_name',
            'sr_process.job_worker_education_id AS education_id',
            'educations.text AS education_name',
            'sr_process.job_layout_id AS job_layout_id',
            'job_layouts.job_title AS job_layout_job_title',
            'sr_process.job_worker_experience_months',
            'sr_process.job_worker_experience_channel',
            'sr_process.job_worker_experience_client',
            'sr_process.job_worker_national',
            'sr_process.job_worker_health_carnet',
            'sr_process.job_worker_health_insurance',
            'sr_process.job_worker_health_disabilities',
            'sr_process.job_list_type',
            'sr_process.job_description',
            'sr_process.stage_group_id',
            'sr_process.screening_phase_id',
            'sr_sts_process_types.id AS status_id',
            'sr_sts_process_types.name AS status_name',
            'sr_process.has_penalty AS has_penalty',
            'jobs.job_slug AS job_slug',
            'recruiter.ID AS recruiter_id',
            'recruiter.first_name AS recruiter_first_name',
            'recruiter.last_name AS recruiter_last_name'
        ]);
        $this->db->from('tbl_staff_requests sr_process');
        $this->db->join('tbl_staff_request_sts_process_types sr_sts_process_types', 'sr_sts_process_types.id=sr_process.sts_process');
        $this->db->join('tbl_job_layouts job_layouts', 'job_layouts.id=sr_process.job_layout_id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.request_ID=sr_process.ID', 'left');
        $this->db->join('tbl_staff_request_type_reasons type_reasons', 'sr_process.reason_request=type_reasons.id', 'left');
        $this->db->join('tbl_campaigns campaigns', 'campaigns.id=sr_process.campaign_id', 'left');
        $this->db->join('tbl_staff_request_job_location_types location_types', 'location_types.id=sr_process.job_location_type_id', 'left');
        $this->db->join('tbl_qualifications educations', 'educations.ID=sr_process.job_worker_education_id', 'left');
        $this->db->join('tbl_employers recruiter', 'recruiter.ID=sr_process.recruiter_ID', 'left');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=sr_process.ID', 'left');
        
        $this->db->where('sr_process.request_model_id', 4);
        $this->db->where('sr_process.company_ID', $employer->company_ID);
        
        //Si el usuario en sesion no es administrador
        //Solo mostrar solicitudes que ha creado o que tiene asignada
        if ($employer->is_admin != 'yes') {
            $this->db->where('(sr_process.recruiter_ID = ' . $employer->ID . ' OR assigned_employers.employer_ID= ' . $employer->ID . ')');
        }
     
        if (isset($input_data['id'])) {
            $this->db->where('sr_process.ID', trim($input_data['id']));
        }

        if (isset($input_data['assigned_employer_id'])) {
            $this->db->where('assigned_employers.employer_ID', trim($input_data['assigned_employer_id']));
        }

        if (isset($input_data['consultant_id'])) {
            $this->db->where('sr_process.no_cia', trim($input_data['consultant_id']));
        }

        if (isset($input_data['client_id'])) {
            $this->db->where('sr_process.cod_clie', trim($input_data['client_id']));
        }

        if (isset($input_data['status'])) {
            $this->db->where('sr_process.sts_process', trim($input_data['status']));
        }   

        if (isset($input_data['has_penalty'])) {
            $this->db->where('sr_process.has_penalty', trim($input_data['has_penalty']));
        }   

        $this->db->group_by('sr_process.ID');

        return $this->db->get()->result();
    }
    
    public function get_sr_proceses($sr_ids)
    {   
        if (count($sr_ids) == 0) {
            return [];
        }

        $this->db->select([
            'COUNT(trc.seeker_ID)'
        ]);
        $this->db->from('tbl_recruitment_candidates trc');
        $this->db->join('tbl_recruitment_process tr_process', 'trc.process_id=tr_process.id');
        $this->db->where('trc.process_id=process.id');
        $sql_count_seeker_in_process = $this->db->get_compiled_select(); 

        $this->db->select([
            'COUNT(trc.seeker_ID)'
        ]);
        $this->db->from('tbl_recruitment_candidates trc');
        $this->db->join('tbl_recruitment_process tr_process', 'trc.process_id=tr_process.id');
        $this->db->where('trc.process_id=process.id');
        $this->db->where('trc.contracted', 1);
        
        $sql_count_seeker_hired = $this->db->get_compiled_select(); 
    
        $this->db->select([
            'process.id',
            'process.request_id',
            'process.country_id AS geographic_country_id',
            'ubigeo_country.country_name AS geographic_country_name',
            'process.department_id AS geographic_department_id',
            'ubigeo.order_administrative1 AS geographic_order_administrative1',
            'process.province_id AS geographic_province_id',
            'ubigeo.order_administrative2 AS geographic_order_administrative2',
            'process.district_id AS geographic_district_id',
            'ubigeo.order_administrative3 AS geographic_order_administrative3',
            'process.commercial_premise_id AS commercial_premise_id',
            'process_cp.name AS commercial_premise_name',
            'zones.id AS zone_id',
            'zones.name AS zone_name',
            'process.vacancies AS vacancies',
            '(' . $sql_count_seeker_in_process . ') AS count_seeker_process',
            '(' . $sql_count_seeker_hired . ') AS count_seeker_hired'
        ]);
        $this->db->from('tbl_recruitment_process process');
        $this->db->join('tbl_commercial_premises process_cp', 'process_cp.id=process.commercial_premise_id', 'left');
        $this->db->join('tbl_countries ubigeo_country', 'process.country_id=ubigeo_country.ID', 'left');
        $this->db->join('tbl_ubigeos ubigeo', 'process.country_id=ubigeo.country_id AND process.district_id=ubigeo.order_administrative3_code', 'left');
        $this->db->join('tbl_ubigeo_zones zones', 'zones.id=process.zone_id', 'left');
       
        $this->db->where_in('process.request_id', $sr_ids);

        $this->db->group_by('process.id');
        $results = $this->db->get()->result();

        $result_data = [];

        foreach ($results as $row) {
            $result_data[$row->request_id][] = $row; 
        }

        return $result_data;
    }

    public function build_data_rs_proceses($job_proceses)
    {
        $process_data = [];

        foreach ($job_proceses as $row_process) {
            $process = [
                'id' => (string)$row_process->id,
                'name' => '',
                'canditates_required_amount' => (int)$row_process->vacancies,
                'candidates_compeeting' => (int)$row_process->count_seeker_process,
                'candidates_hired' => (int)$row_process->count_seeker_hired,
            ]; 

            if ($row_process->geographic_country_id) {
                $process['name'] = $row_process->geographic_order_administrative1 . ', ' . $row_process->geographic_order_administrative2 . ', ' . $row_process->geographic_order_administrative3;
            }

            if ($row_process->commercial_premise_id) {
                $process['name'] = $row_process->commercial_premise_name;
            }

            if ($row_process->zone_id) {
                $process['name'] = $row_process->zone_name;
            }

            $process_data[] = $process;
        }

        return $process_data;
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

    private function build_data_request_skills($data_seeker_skills)
    {
        $data_skills = [];

        foreach ($data_seeker_skills as $row) {
            $data_skills[] = [
                'id' => (int)$row->id,
                'name' => $row->name
            ];
        }

        return $data_skills;
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

    private function build_data_request_working_days($working_days)
    {
        $data_working_days = [];

        foreach ($working_days as $row) {
            $data_working_days[] = [
                'day' => (int)$row->day
            ];
        }

        return $data_working_days;
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

    private function build_data_request_job_experiences($experiences)
    {
        $data_xperiences = [];

        foreach ($experiences as $row) {
            $data_xperiences[] = [
                'id' => (int)$row->id,
                'name' => $row->name
            ];
        }

        return $data_xperiences;
    }
}
