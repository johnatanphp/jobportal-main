<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_scheduling_list_service
{
    public function __construct()
	{
		//Load models
        $this->load->model('Exam_document');
        $this->load->model('Exam_request_seeker');
        $this->load->model('Exam_request_schedule');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return apiv2_response(
                false,
                $message
            );
        }
    
        return $this->list($params);  
    }

    private function validate($params)
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('request_id', 'request_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'integer|greater_than[0]');
        $this->form_validation->set_rules('candidate_identification_document_number', 'candidate_identification_document_number', 'integer|greater_than[0]');
        $this->form_validation->set_rules('stage_id', 'stage_id', 'integer|greater_than[0]');
        $this->form_validation->set_rules('notified_sso', 'notified_sso', 'in_list[1,0]');
    
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }
        
        return [true, 'OK'];
    }

    public function list($params)
    {    
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_type',
            'seekers.document_number',
            'seekers.first_name',
            'exam_request_seekers.id AS exam_seeker_id',
            'exam_request_seekers.scheduled_date',
            'exam_request_seekers.exam_type_id AS exam_type',
            'exam_request_seekers.exam_date',
            'exam_request_seekers.exam_time',
            'exam_request_seekers.comment AS comments',
            'exam_request_seekers.ubigeo',
            'exam_request_seekers.status',
            'exam_request_seekers.notified_sso',
            'exam_request_seekers.medical_center_name AS medical_center_name',
            'exam_request_seekers.medical_center_location_name',
            'exam_request_seekers.medical_center_location_address AS medical_center_location_address',
            'exam_request_status.id AS exam_request_status_id',
            'exam_request_status.name AS exam_request_status_name',
            'ubigeos.order_administrative1_code AS order_administrative1_code',
            'ubigeos.order_administrative1 AS order_administrative1_name',
            'ubigeos.order_administrative2_code AS order_administrative2_code',
            'ubigeos.order_administrative2 AS order_administrative2_name',
            'ubigeos.order_administrative3_code AS order_administrative3_code',
            'ubigeos.order_administrative3 AS order_administrative3_name',
            'exam_request_results.result_file AS result_path_file',
            'exam_request_results.observations AS result_observations',
            'exam_request_result_types.result_name AS result_status_name',
            'requests.ID AS request_id'
        ]);
        $this->db->from('tbl_exam_request_seekers exam_request_seekers');
        $this->db->join('tbl_exam_request_status exam_request_status', 'exam_request_status.id=exam_request_seekers.status');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID = exam_request_seekers.seeker_id');
        $this->db->join('tbl_recruitment_candidates candidates', 'candidates.seeker_ID=exam_request_seekers.seeker_id AND candidates.job_ID=exam_request_seekers.job_id');
        $this->db->join('tbl_post_jobs jobs', 'candidates.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=jobs.request_ID');
       //$this->db->join('tbl_medical_centers medical_centers', 'exam_request_seekers.medical_center_code = medical_centers.code', 'left');
        $this->db->join('tbl_ubigeos ubigeos', "ubigeos.country_id=56 AND exam_request_seekers.ubigeo = CONCAT(ubigeos.order_administrative1, ', ', ubigeos.order_administrative2, ', ', ubigeos.order_administrative3)", 'left');
        $this->db->join('tbl_exam_request_results exam_request_results', 'exam_request_seekers.id = exam_request_results.exam_request_seeker_id', 'left');
        $this->db->join('tbl_exam_request_result_types exam_request_result_types', 'exam_request_result_types.id = exam_request_results.result_status', 'left');
        
        $this->db->where('exam_request_seekers.active', 1);
        
        if (isset($params['id'])) {
            $this->db->where_in('exam_request_seekers.id', $params['id']);
        }

        if (isset($params['request_id'])) {
            $this->db->where('requests.ID', $params['request_id']);
        }

        if (isset($params['candidate_id'])) {
            $this->db->where('seekers.ID', $params['candidate_id']);
        }

        if (isset($params['candidate_identification_document_number'])) {
            $this->db->where('seekers.document_number', $params['candidate_identification_document_number']);
        }

        if (isset($params['stage_id'])) {
            $this->db->where('candidates.stage', $params['stage_id']);
        }
        
        if (isset($params['notified_sso'])) {
            $this->db->where('exam_request_seekers.notified_sso', $params['notified_sso']);
        }
        
        $this->db->order_by('exam_request_seekers.scheduled_date', 'ASC');

        $result = $this->db->get()->result();

        $response_data = [];

        foreach ($result as $row_data) {

            $result_data = [];

            if ($row_data->result_status_name) {
                $result_data[] = [
                    'result' => $row_data->result_status_name ? $row_data->result_status_name : null,
                    'observations' => trim((string)$row_data->result_observations),
                    'file_attached_url' => $row_data->result_path_file ? file_url($row_data->result_path_file) : null
                ];
            }

            $request = null;

            if ($row_data->request_id) {
                $request = [
                    'id' => (string)$row_data->request_id
                ];
            }

            $exam_location = [
                'country_id' => '56',
                'country_name' => 'Perú',
                'department_name' => (string)$row_data->order_administrative1_name,
                'department_id' => (string)$row_data->order_administrative1_code,
                'department_name' => (string)$row_data->order_administrative1_name,
                'province_id' => (string)$row_data->order_administrative2_code,
                'province_name' => (string)$row_data->order_administrative2_name,
                'district_id' => (string)$row_data->order_administrative3_code,
                'district_name' => (string)$row_data->order_administrative3_name,
            ];

            $status = [
                'id' => (string)$row_data->exam_request_status_id,
                'name' => (string)$row_data->exam_request_status_name,
            ];

            $medical_center = null;

            if ($row_data->medical_center_name) {
                $medical_center = [
                    'name' => $row_data->medical_center_name,
                    'location_name' => $row_data->medical_center_location_name,
                    'address' => $row_data->medical_center_location_address
                ];
            }

            $response_data[] = [
                'id' => (string)$row_data->exam_seeker_id,
                'candidate_id' => (string)$row_data->seeker_id,
                'candidate_identification_document_number' => $row_data->document_number,
                'candidate_first_name' => (string)$row_data->first_name,
                'exam_date' => $row_data->exam_date ? $row_data->exam_date : $row_data->scheduled_date,
                'exam_time' => $row_data->exam_time ? $row_data->exam_time : null,
                'exam_location' => $exam_location,
                'sent_to_sso' => (int)$row_data->notified_sso,
                'status' => $status,
                'request' => $request,
                'medical_center' => $medical_center,
                'results' => $result_data,
                'comments' => (string)$row_data->comments
            ];
        }

        return apiv2_response(
            true, 
            'Ok',
            $response_data
        );
    }
}
