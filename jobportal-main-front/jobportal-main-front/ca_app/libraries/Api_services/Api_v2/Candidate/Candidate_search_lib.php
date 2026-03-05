<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_search_lib
{
    private $pagination_per_page;

    private $pagination_page;

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function search($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }
     
        return  $this->search_candidate($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('per_page', 'per_page', 'integer|greater_than[0]|less_than_equal_to[25]');
        $this->form_validation->set_rules('page', 'page', 'integer|greater_than[0]');

        if (isset($params['email'])) {
            $this->form_validation->set_rules('email', 'email', 'trim|valid_email');
        }

        if (isset($params['identification_document_type_id']) || isset($params['identification_document_number'])) {
            $this->form_validation->set_rules('identification_document_type_id', 'identification_document_type_id', 'trim|required|integer|greater_than[0]');
            $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'trim|required');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        $form_is_success = $this->form_validation->run();
        $message_error = $this->form_validation->error_array();

        if (!$form_is_success && current($message_error) === false) {
            return [false, 'Debe indicar algún filtro para realizar la búsqueda'];
        }

        if (!$form_is_success) {  
           return [false, current($message_error)];
        }

        return [true, 'OK'];
    }

    public function search_candidate($params)
    {
        $pagination = $this->create_pagination($params);

        $this->get_data_employee_overall($params);

        $this->db->select([
            'js.ID AS seeker_id',
            'js.email AS seeker_email',
            'js.first_name AS seeker_first_name',
            'js.paternal_last_name AS seeker_paternal_last_name',
            'js.maternal_last_name AS seeker_maternal_last_name',
            'document_type.name AS seeker_document_type_name',
            'document_type.abbreviation AS seeker_document_type_name_abbreviation',
            'js.document_number AS seeker_document_number',
            'js.mobile AS seeker_phone_number',
            'js.gender AS seeker_gender',
            'js.dob AS seeker_dob',
            'js.photo AS seeker_avatar',
            'seeker_countries.country_name AS seeker_country_name',
            'seeker_countries.iso_3166_1_alpha2 AS seeker_country_iso_3166_1_alpha2',
            'employee_overall_data.status AS overall_status',
            'employee_overall_data.overall_accumulated_working_time_days',
            'employee_overall_data.blacklisted AS blacklisted',
        ]);
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_identity_document_types document_type', 'js.document_type=document_type.id');
        $this->db->join('tbl_countries seeker_countries', 'seeker_countries.ID=js.country', 'left');
        $this->db->join('tbl_employee_overall_data employee_overall_data', 'employee_overall_data.document_number=js.document_number', 'left');

        if (isset($params['email'])) {
            $this->db->where('js.email', trim($params['email']));
        }

        if (isset($params['identification_document_type_id'])) {
            $this->db->where('js.document_type', trim($params['identification_document_type_id']));
            $this->db->where('js.document_number', trim($params['identification_document_number']));
        }
      
        $this->db->limit($this->pagination_per_page);
        $this->db->offset(($this->pagination_page - 1) * $this->pagination_per_page);
        
        $results = $this->db->get()->result();

        //Consutar los procesos abiertos de los postulantes
        $data_current_processes = $this->get_total_current_processes($results);

        $candidates = [];
        
        foreach ($results as $candidate) {
            
            $current_processes = isset($data_current_processes[$candidate->seeker_id]) ? ($data_current_processes[$candidate->seeker_id])->total_processes : 0;
            
            $candidate->current_processes = (int)$current_processes;
            $candidate_card = apiv2_candidate_card($candidate);
            $candidates[] = $candidate_card;
        }

        $response = apiv2_response(true, 'Ok', $candidates);
        $response['pagination'] = $pagination;

        return $response;
    }

    private function create_pagination($params)
    {
        $this->pagination_per_page = isset($params['per_page']) && $params['per_page'] <= 25 ?  $params['per_page'] : 25;
        $this->pagination_page = isset($params['page']) ? $params['page'] : 1;

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

    private function total_result_candidates($params)
    {
        $this->db->select([
            'js.ID AS seeker_id'
        ]);
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_identity_document_types document_type', 'js.document_type=document_type.id');

        if (isset($params['email'])) {
            $this->db->where('js.email', trim($params['email']));
        }

        if (isset($params['identification_document_type_id'])) {
            $this->db->where('js.document_type', trim($params['identification_document_type_id']));
            $this->db->where('js.document_number', trim($params['identification_document_number']));
        }
        
        return $this->db->count_all_results();
    }

    private function get_data_employee_overall($params) 
    {
        $this->db->select('js.document_number');
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_countries countries', 'js.country=countries.ID');
        $this->db->where('countries.iso_3166_1_alpha2', 'PE'); //Peru
        
        if (isset($params['email'])) {
            $this->db->where('js.email', trim($params['email']));
        }

        if (isset($params['identification_document_type_id'])) {
            $this->db->where('js.document_type', trim($params['identification_document_type_id']));
            $this->db->where('js.document_number', trim($params['identification_document_number']));
        }

        $this->db->limit($this->pagination_per_page);
        $this->db->offset(($this->pagination_page - 1) * $this->pagination_per_page);
       
        $result_document_numbers = $this->db->get()->result_array();

        $document_numbers = array_column($result_document_numbers, 'document_number');

        $this->load->library('Jobseeker/Employee_overall_data_lib');

        $this->employee_overall_data_lib->migrate($document_numbers);
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
    
    private function build_seeker_ids($candidates = [])
    {   
        $seeker_ids = [];

        foreach ($candidates as $row) {
            $seeker_ids[] = $row->seeker_id;
        }

        return $seeker_ids;
    }
}
