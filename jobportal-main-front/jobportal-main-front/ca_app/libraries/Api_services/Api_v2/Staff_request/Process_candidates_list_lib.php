<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_list_lib
{
    private $pagination_per_page;

    private $pagination_page;

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($params)
    {
        $this->load->model('Job_seeker');

        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }

        return $this->list_candidates($params);
    }

    public function list_candidates($params)
    {
        //Crear data paginacion
        $pagination = $this->create_pagination($params);

        //Consultar datos empleaos en overall
        $this->get_data_employee_overall($params); 
        
        //Consultar datos candidatos
        $candidates = $this->get_result_candidates($params);

        //Consutar los preocesos abiertos de los postulantes
        $data_current_processes = $this->get_total_current_processes($candidates);

        //Consultar contador de candidatos descartados / rechazados
        $total_rejected_candidates = $this->total_rejected_candidates($params);
    
        $result_candidates = [];

        foreach ($candidates as $row) {
            $candidate_data = [];
            $current_processes = isset($data_current_processes[$row->seeker_id]) ? ($data_current_processes[$row->seeker_id])->total_processes : 0;
            
            $rejection = null;
            $rejected = (int)$row->rejected;

            $row->current_processes = $current_processes;
            $candidate_card = apiv2_candidate_card($row);

            //dd($row);
            $candidate_data = [
                'stage' => [
                    'id' =>  $row->stage_id,
                    'name' => $row->stage_name
                ],
                'hired' => (int)$row->seeker_hired,
                'is_rejected' => $rejected
            ];
        
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
            
            if ($rejected) {
                $candidate_data['rejection'] = [
                    'rejection_date' => $row->rejected_date,
                    'rejected_by' => [
                        'id' => $row->rejected_by_id,
                        'first_name' => trim((string)$row->rejected_by_first_name),
                        'last_name' => trim((string)$row->rejected_by_last_name),
                    ]
                ];
            }
            
            $candidate_data = array_merge($candidate_card, $candidate_data);

            $result_candidates[] = $candidate_data;
        }

        $response = apiv2_response(true, 'OK', $result_candidates);
        $response['pagination'] = $pagination;
        $response['meta'] = [
            'total_rejected_candidates' => (int)$total_rejected_candidates
        ];

        return $response; 
    }

    private function get_result_candidates($params)
    {
        $this->db->select([
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
            'rc_stage.id AS stage_id',
            'rc_stage.name AS stage_name',
            'rc.contracted AS seeker_hired',
            'rc.discarded AS rejected',
            'CONCAT(rc.rejected_date, " ", rc.rejected_time) AS rejected_date',
            'rejected_employers.ID AS rejected_by_id',
            'rejected_employers.first_name AS rejected_by_first_name',
            'rejected_employers.last_name AS rejected_by_last_name',
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
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->join('tbl_job_seekers js', 'rc.seeker_ID=js.ID');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.id=rc.process_id');
        $this->db->join('tbl_recruitment_stages rc_stage', 'rc.stage=rc_stage.id');
        $this->db->join('tbl_employers added_by_employer', 'added_by_employer.ID=rc.created_by', 'left');
        $this->db->join('tbl_recruitment_candidate_sources candidate_sources', 'candidate_sources.process_id=rc.process_id AND candidate_sources.seeker_id=rc.seeker_ID', 'left');
        $this->db->join('tbl_recruitment_sources recruitment_sources', 'recruitment_sources.id=candidate_sources.source_id', 'left');
        $this->db->join('tbl_social_networks social_networks', 'social_networks.id=candidate_sources.social_network_id', 'left');
        $this->db->join('tbl_employee_overall_data employee_overall_data', 'employee_overall_data.document_number=js.document_number', 'left');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=js.document_type', 'left');
        $this->db->join('tbl_countries seeker_countries', 'seeker_countries.ID=js.country', 'left');
        $this->db->join('tbl_employers rejected_employers', 'rejected_employers.ID=rc.rejected_by_user', 'left');

        if (isset($params['id'])) {
            $this->db->where('sr_process.request_id', $params['id']);
        }
        
        if (isset($params['stage_id'])) {
            $this->db->where('rc.stage', $params['stage_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('rc.process_id', $params['process_id']);
        }

        if (isset($params['rejected'])) {
            $this->db->where('rc.discarded', $params['rejected']);
        }

        if (isset($params['blacklisted'])) {
            $this->db->where('employee_overall_data.blacklisted', $params['blacklisted']);
        }

        if (isset($params['hired'])) {
            $this->db->where('rc.contracted', $params['hired']);
        }

        if (isset($params['candidate_id'])) {
            $this->db->where('rc.seeker_ID', $params['candidate_id']);
        }
        
        $this->db->limit($this->pagination_per_page);
        $this->db->offset(($this->pagination_page - 1) * $this->pagination_per_page);
        
        return $this->db->get()->result();
    }

    private function total_result_candidates($params)
    {
        $this->db->select([
            'rc.seeker_ID AS seeker_id'
        ]);
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->join('tbl_job_seekers js', 'rc.seeker_ID=js.ID');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.id=rc.process_id');
        $this->db->join('tbl_employee_overall_data employee_overall_data', 'employee_overall_data.document_number=js.document_number', 'left');

        if (isset($params['id'])) {
            $this->db->where('sr_process.request_id', $params['id']);
        }

        if (isset($params['stage_id'])) {
            $this->db->where('rc.stage', $params['stage_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('rc.process_id', $params['process_id']);
        }

        if (isset($params['rejected'])) {
            $this->db->where('rc.discarded', $params['rejected']);
        }

        if (isset($params['blacklisted'])) {
            $this->db->where('employee_overall_data.blacklisted', $params['blacklisted']);
        }

        if (isset($params['hired'])) {
            $this->db->where('rc.contracted', $params['hired']);
        }

        if (isset($params['candidate_id'])) {
            $this->db->where('rc.seeker_ID', $params['candidate_id']);
        }
    
        return $this->db->count_all_results();
    }

    private function create_pagination($params)
    {
        $this->pagination_per_page = isset($params['per_page']) && $params['per_page'] <= 25 ?  $params['per_page'] : 25;
        $this->pagination_page = isset($params['page']) ? $params['page']: 1;

        $current_page = (int)$this->pagination_page;
        $total_records = $this->total_result_candidates($params);

        $total_pages = ceil($total_records / $this->pagination_per_page);

        return [
            'count' => (int)$total_records,
            'pages' => $total_pages,
            'page' => $current_page <= $total_pages ? $current_page : null,
            'next' => $total_pages > 0 && $current_page < $total_pages ? $current_page + 1 : null,
            'prev' => $current_page > 1 && $current_page <= $total_pages ? $current_page - 1 : null
        ];
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('id', 'id', 'required');
        $this->form_validation->set_rules('stage_id', 'stage_id', 'integer|greater_than[0]');
        $this->form_validation->set_rules('per_page', 'per_page', 'integer|greater_than[0]|less_than_equal_to[25]');
        $this->form_validation->set_rules('page', 'page', 'integer|greater_than[0]');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        return [true, 'OK'];
    }

    private function build_seeker_ids($candidates = [])
    {   
        $seeker_ids = [];

        foreach ($candidates as $row) {
            $seeker_ids[] = $row->seeker_id;
        }

        return $seeker_ids;
    }

    private function get_total_current_processes($candidates)
    {
        $seeker_ids= $this->build_seeker_ids($candidates);

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

    private function total_rejected_candidates($params)
    {
        $this->db->select([
            'rc.seeker_ID AS seeker_id'
        ]);
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.id=rc.process_id');
        $this->db->where('rc.discarded', 1);

        if (isset($params['id'])) {
            $this->db->where('sr_process.request_id', $params['id']);
        }
        
        if (isset($params['stage_id'])) {
            $this->db->where('rc.stage', $params['stage_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('rc.process_id', $params['process_id']);
        }

        return $this->db->count_all_results();
    }

    private function get_data_employee_overall($params) 
    {
        $this->db->select('document_number');
        $this->db->from('tbl_recruitment_process processes');
        $this->db->join('tbl_recruitment_candidates rc', 'processes.id=rc.process_id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=rc.seeker_ID');
        $this->db->join('tbl_countries countries', 'candidates.country=countries.ID');
        $this->db->where('countries.iso_3166_1_alpha2', 'PE'); //Peru

        if (isset($params['id'])) {
            $this->db->where('processes.request_id', $params['id']);
        } else {
            $this->db->where('processes.id', $params['process_id']);

            if (isset($params['candidate_id'])) {
                $this->db->where('rc.seeker_id', $params['candidate_id']);
            }
        }

        $this->db->limit(200);
       
        $result_document_numbers = $this->db->get()->result_array();

        $document_numbers = array_column($result_document_numbers, 'document_number');

        $this->load->library('Jobseeker/Employee_overall_data_lib');

        $this->employee_overall_data_lib->migrate($document_numbers);
    }
}
