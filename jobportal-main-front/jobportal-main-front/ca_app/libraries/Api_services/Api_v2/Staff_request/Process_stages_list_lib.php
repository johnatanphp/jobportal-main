<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_stages_list_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($params)
    {
        $result_data = [];
        $process_ids = [];

        $this->get_data_employee_overall($params);

        $results_proceses = $this->get_proceses($params);

        foreach ($results_proceses as $row) {
            $process_ids[] = $row->id;
        }

        $data_stages = $this->get_stages($process_ids);

        foreach ($results_proceses as $row) {
            $result_data[] = [
                'process_id' => (string)$row->id,
                'stages' => $data_stages[$row->id] ?? [],
                'rejected' => [],
                'black_list' => []
            ];
        }

        return [
            'status' => true,
            'message' => 'OK',
            'data' => $result_data
        ];       
    }
    
    public function get_proceses($params)
    {   
        $this->db->select([
            'process.id',
            'process.request_id',
        ]);
        $this->db->from('tbl_recruitment_process process');        
        $this->db->where_in('process.request_id', $params['request_id']);

        $this->db->group_by('process.id');

        return $this->db->get()->result();
    }
    
    public function get_stages($process_ids)
    {
        if (count($process_ids) == 0) {
            return [];
        }

        $this->db->select([
            'job_processes.id AS process_id',
            'stages.id AS stage_id',
            'stages.name AS stage_name',
            'stages.order As stage_order',
            'stages.description AS stage_description',
            'stage_category.id AS stage_category_id', 
            'stage_category.name AS stage_category_name',
            'COUNT(rc.seeker_ID) As stage_count_seekers',
            'js.ID AS seeker_id',
            'js.email AS seeker_email',
            'js.first_name AS seeker_first_name',
            'js.paternal_last_name AS seeker_paternal_last_name',
            'js.maternal_last_name AS seeker_maternal_last_name',
            'doc_type.name AS seeker_document_type_name',
            'doc_type.abbreviation AS seeker_document_type_name_abbreviation',
            'js.document_number AS seeker_document_number',
            'js.gender AS seeker_gender',
            'js.dob AS seeker_dob',
            'js.photo AS seeker_avatar',
            'js.mobile AS seeker_phone_number',
            'seeker_countries.country_name AS seeker_country_name',
            'seeker_countries.iso_3166_1_alpha2 AS seeker_country_iso_3166_1_alpha2',
            'employee_overall_data.status AS overall_status',
            'employee_overall_data.overall_accumulated_working_time_days',
            'employee_overall_data.blacklisted AS blacklisted',
            'rc.creation_date AS added_at',
            'added_by_employer.ID AS added_by_employer_id',
            'added_by_employer.first_name AS added_by_employer_first_name',
            'added_by_employer.last_name AS added_by_employer_last_name',
            'recruitment_sources.id AS source_id',
            'recruitment_sources.name AS source_name',
            'social_networks.id AS source_social_network_id',
            'social_networks.name AS source_social_network_name',
            'candidate_sources.referred_by_identification_document_number AS source_referred_by_identification_document_number',
            'candidate_sources.referred_by_first_name AS source_referred_by_first_name',
            'candidate_sources.referred_by_last_name AS source_referred_by_last_name',
        ]);

        $this->db->from('tbl_recruitment_process job_processes');
        $this->db->join('tbl_staff_requests request', 'request.ID=job_processes.request_id');
        $this->db->join('tbl_recruitment_stages stages', 'request.stage_group_id=stages.stage_group_id');
        $this->db->join('tbl_recruitment_stages_categories stage_category', 'stages.stage_category_id=stage_category.id');
        $this->db->join('tbl_recruitment_candidates rc', 'rc.stage=stages.id AND rc.process_id=job_processes.id AND rc.discarded=0', 'left');
        $this->db->join('tbl_employers added_by_employer', 'added_by_employer.ID=rc.created_by', 'left');
        $this->db->join('tbl_recruitment_candidate_sources candidate_sources', 'candidate_sources.process_id=rc.process_id AND candidate_sources.seeker_id=rc.seeker_ID', 'left');
        $this->db->join('tbl_recruitment_sources recruitment_sources', 'recruitment_sources.id=candidate_sources.source_id', 'left');
        $this->db->join('tbl_social_networks social_networks', 'social_networks.id=candidate_sources.social_network_id', 'left');
        $this->db->join('tbl_job_seekers js', 'rc.seeker_ID=js.ID', 'left');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=js.document_type', 'left');
        $this->db->join('tbl_countries seeker_countries', 'seeker_countries.ID=js.country', 'left');
        $this->db->join('tbl_employee_overall_data employee_overall_data', 'employee_overall_data.document_number=js.document_number', 'left');

        $this->db->where('stages.active', 1);
        $this->db->where_in('job_processes.id', $process_ids);

        $this->db->group_by(['job_processes.id', 'stages.id']);

        $results = $this->db->get()->result();

        //dd($process_ids);
        $process = [];

        //Consutar los preocesos abiertos de los postulantes
        $data_current_processes = $this->get_total_current_processes($results);

        foreach ($results as $row) {

            $current_processes = isset($data_current_processes[$row->seeker_id]) ? ($data_current_processes[$row->seeker_id])->total_processes : 0;
        
            $stage_row = [
                'id' => (string)$row->stage_id,
                'name' => (string)$row->stage_name,
                'order' => (int)$row->stage_order,
                'description' => (string)$row->stage_description,
                'category_id' => (string)$row->stage_category_id,
                'category_name' => (string)$row->stage_category_name,
                'candidate_count' => (int)$row->stage_count_seekers,
                'candidate' => []
            ];

            if ($row->stage_count_seekers > 0) {
                $row->current_processes = (int)$current_processes;
                
                $candidate_data = apiv2_candidate_card($row);
                
                $candidate_data['added_at'] = $row->added_at . ' 00:00:00';    
                $candidate_data['added_by'] = null;
                
                if ($row->added_by_employer_id) {
                     $candidate_data['added_by'] = [
                        'id' => $row->added_by_employer_id,
                        'first_name' => $row->added_by_employer_first_name,
                        'last_name' => $row->added_by_employer_last_name
                    ];
                }
                
                $candidate_data['source'] = null;
                
                if ($row->source_id) {
                    $source = [
                        'id' => $row->source_id,
                        'name' => $row->source_name,
                        'social_network' => null,
                        'referred_by' => null
                    ];
                    
                    if ($row->source_social_network_id) {
                        $source['social_network'] = [
                            'id' => $row->source_social_network_id,
                            'name' => $row->source_social_network_name
                        ];
                    }
                    
                    if ($row->source_referred_by_identification_document_number) {
                        $source['referred_by'] = [
                            'identification_document_number' => $row->source_referred_by_identification_document_number,
                            'first_name' => $row->source_referred_by_first_name,
                            'last_name' => $row->source_referred_by_last_name
                        ];
                    }
                    
                    $candidate_data['source'] = $source;
                }
                
                $candidate_data['rejection'] = null;
                
                $stage_row['candidate'] = $candidate_data;
            }

            $process[$row->process_id][] = $stage_row;
        }

        return $process;
    }

    private function get_total_current_processes($results_stages)
    {
        $seeker_ids = [];

        foreach ($results_stages as $row) {

            if (empty($row->seeker_id)) {
                continue;
            }

            $seeker_ids[] = $row->seeker_id;
        }

        if (count($seeker_ids) == 0) {
            return [];
        }

        $this->db->select([
            'rc.seeker_ID AS seeker_id',
            'COUNT(rc.seeker_id) AS total_processes'
        ]);

        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->where_in('rc.seeker_ID', $seeker_ids);
        $this->db->where('rc.contracted', 0);
        $this->db->where('rc.discarded', 0);

        $this->db->group_by('rc.seeker_ID');
        
        $results = $this->db->get()->result();

        $totals = [];

        foreach ($results as $row) {
            $totals[$row->seeker_id] = $row;
        }

        return $totals;
    }

    private function get_data_employee_overall($params) 
    {
        $this->db->select('candidates.document_number AS document_number');
        $this->db->from('tbl_recruitment_process processes');
        $this->db->join('tbl_recruitment_candidates rc', 'processes.id=rc.process_id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=rc.seeker_ID');
        $this->db->join('tbl_countries countries', 'candidates.country=countries.ID');
        $this->db->where('countries.iso_3166_1_alpha2', 'PE'); //Peru
        
        if (isset($params['id'])) {
            $this->db->where('processes.request_id', $params['request_id']);
        }

        $this->db->limit(100);
       
        $result_document_numbers = $this->db->get()->result_array();

        $document_numbers = array_column($result_document_numbers, 'document_number');

        $this->load->library('Jobseeker/Employee_overall_data_lib');

        $this->employee_overall_data_lib->migrate($document_numbers);
    }
}
