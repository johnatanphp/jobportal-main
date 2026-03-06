<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_scheduling_create_service
{
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

        return $this->create($params);  
    }

    public function validate($params)
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('request_id', 'request_id', 'required|in_list_db[tbl_staff_requests.ID]');
        $this->form_validation->set_rules('exam_candidates[]', 'exam_candidates[]', 'required');

        $exam_candidates = $params['exam_candidates'] ?? [0];

        foreach ($exam_candidates as $exam_candidate_index => $exam_candidate) {

            $country_id = $exam_candidate['country_id'] ?? '';
            $department_id = $exam_candidate['department_id'] ?? '';
            $province_id = $exam_candidate['province_id'] ?? '';
            $district_id = $exam_candidate['district_id'] ?? '';
            $medical_center = $exam_candidate['medical_center'] ?? null;

            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][candidate_id]', 'exam_candidates[' . $exam_candidate_index . '][candidate_id]', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][exam_date]', 'exam_candidates[' . $exam_candidate_index . '][exam_date]', 'required|valid_date|date_greater_than_equal_to[' . date('Y-m-d') . ']');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][country_id]', 'exam_candidates[' . $exam_candidate_index . '][country_id]', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][department_id]', 'exam_candidates[' . $exam_candidate_index . '][department_id]', 'required');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][province_id]', 'exam_candidates[' . $exam_candidate_index . '][province_id]', 'required');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][district_id]', 'exam_candidates[' . $exam_candidate_index . '][district_id]', 'required');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][exam_id]', 'exam_candidates[' . $exam_candidate_index . '][exam_id]', 'required|in_list[1]');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][exam_type_id]', 'exam_candidates[' . $exam_candidate_index . '][exam_type_id]', 'required|in_list_db[tbl_exam_emo_types.id]');
            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][comments]', 'exam_candidates[' . $exam_candidate_index . '][comments]', 'max_length[180]');
            $this->form_validation->set_rules(
                'exam_candidates[' . $exam_candidate_index . ']', 'exam_candidates[' . $exam_candidate_index . ']', [['check_ubigeo', function() use ($country_id, $department_id, $province_id, $district_id) {
                    return $this->check_ubigeo($country_id,  $department_id, $province_id, $district_id);
                }]
            ]);

            if ($medical_center) {
                $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][medical_center][code]', 'exam_candidates[' . $exam_candidate_index . '][medical_center][code]', 'required');
                $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][medical_center][name]', 'exam_candidates[' . $exam_candidate_index . '][medical_center][name]', 'required');
                $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][medical_center][location_code]', 'exam_candidates[' . $exam_candidate_index . '][medical_center][location_code]', 'required');                
                $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][medical_center][location_name]', 'exam_candidates[' . $exam_candidate_index . '][medical_center][location_name]', 'required'); 
            }

            $this->form_validation->set_rules('exam_candidates[' . $exam_candidate_index . '][comments]', 'exam_candidates[' . $exam_candidate_index . '][comments]', 'max_length[180]');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');
        $this->form_validation->set_message('check_ubigeo', 'La relación del ubigeo del campo %s no existe en el maestro de ubigeos');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        $max_exam_sheduling = 10;

        if (count($exam_candidates) > $max_exam_sheduling) {
            return [false, 'Maximo de programaciones a registar por envio es de ' . $max_exam_sheduling];
        }

        $request_id = $params['request_id'];
        $exam_candidates = $params['exam_candidates'];

        $staff_request = $this->Staff_request->find($request_id);
        $job = $this->Posted_job->find(['request_ID' => $request_id]);

        foreach ($exam_candidates as $exam_candidate_index => $exam_candidate) {
        
            $this->db->from('tbl_exam_request_seekers');
            $this->db->where('job_ID', $job->ID);
            $this->db->where('seeker_ID', $exam_candidate['candidate_id']);
            $this->db->where('exam_type_id', 1);
            $this->db->where('active', 1);
            $exam_schedule =  $this->db->get()->row();

            if ($exam_schedule && $exam_schedule->status != 7) {
                return [
                    false, 
                    'EL registro exam_candidates[' . $exam_candidate_index . ']' . ' no puede ser guardado porque ya esta creado'
                ];
            }
        }

        return [true, 'OK'];
    }

    public function create($params)
    {
        $this->load->model('Exam_request_seeker');
        $this->load->model('Exam_emo_type');

        $exam_candidates = $params['exam_candidates'];
        $request_id = $params['request_id'];
        //$user_id = $params['user_id'];
    
        $staff_request = $this->Staff_request->find($request_id);
        $job = $this->Posted_job->find(['request_ID' => $request_id]);

        $job_id = $job->ID;

        $exam_request_seeker_ids = [];

        // Iniciar transacción
        $this->db->trans_start();

        foreach ($exam_candidates as $exam_candidate) {
            $exam_id = $exam_candidate['exam_id'];
            $exam_type_id = $exam_candidate['exam_type_id'];
            $candidate_id = $exam_candidate['candidate_id'];
            $exam_date = $exam_candidate['exam_date'];
            $comment = trim($exam_candidate['comments']);
            $medical_center = $exam_candidate['medical_center'] ?? null;

            $ubigeo = $this->get_ubigeo($exam_candidate);
         
            $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create($job_id, $candidate_id, $exam_id);

            if (!$exam_request_seeker_id) {
                continue;
            }

            $exam_request = $this->Exam_request_seeker->find($exam_request_seeker_id);

            if (!$exam_request) {
                continue;
            }

            if ($exam_request->status == 7) {
                $this->db->where('id', $exam_request_seeker_id);
                $this->db->update('tbl_exam_request_seekers', [
                    'active' => 0
                ]);
                $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create($job_id, $candidate_id, $exam_id);

                if (!$exam_request_seeker_id) {
                    continue;
                }
            }

            $exam_type = $this->Exam_emo_type->find($exam_type_id);

            if (!$exam_type) {
                continue;
            }

            $data_exam_seeker = [
                'scheduled_date' => $exam_date,
                'status' => 1,
                'ubigeo' => $ubigeo,
                'comment' => $comment,
                'exam_doc_type' => $exam_type->name,
                'medical_center_code' => $medical_center['code'] ?? '',
                'medical_center_name' => $medical_center['name'] ?? '',
                'medical_center_location_code' => $medical_center['location_code'] ?? '',
                'medical_center_location_name' => $medical_center['location_name'] ?? ''
            ];

            $this->db->where('id', $exam_request_seeker_id);
            $this->db->update('tbl_exam_request_seekers', $data_exam_seeker);   

            $exam_request_seeker_ids[] = $exam_request_seeker_id;
        }
    
        // Completar transacción
        $this->db->trans_complete();
    
        if (!$this->db->trans_status()) {
            return apiv2_response(
                false, 
                'Ocurrió un error al procesar la programación de los exámenes.'
            );
        }

        $this->load->library('Api_services/Api_v2/Exam_scheduling/Exam_scheduling_list_service');
        $response = $this->exam_scheduling_list_service->exec([
            'request_id' => $request_id, 
            'id' => $exam_request_seeker_ids
        ]);

        return apiv2_response(
            true, 
            'La programación de los exámenes se programaron con éxito.',
            $response['status'] == true && isset($response['data']) ? $response['data'] : []
        );
    }

    public function get_ubigeo($params)
    {
        $this->db->from('tbl_ubigeos');
        $this->db->where('country_id', $params['country_id']);
        $this->db->where('order_administrative1_code', $params['department_id']);
        $this->db->where('order_administrative2_code', $params['province_id']);
        $this->db->where('order_administrative3_code', $params['district_id']);

        $ubigeo = $this->db->get()->row();

        if (!$ubigeo) {
            return null;
        }

        return $ubigeo->order_administrative1 . ', ' .  $ubigeo->order_administrative2 . ', ' .  $ubigeo->order_administrative3;
    }

    public function check_ubigeo($country_id, $department_id, $province_id, $district_id)
    {
        $count = $this->db->get_where(
            'tbl_ubigeos', [
                'country_id' => $country_id,
                'order_administrative1_code' => $department_id,
                'order_administrative2_code' => $province_id,
                'order_administrative3_code' => $district_id
            ]
        )->num_rows();

        if ($count == 0) {
            return false;
        }

        return true;
    }
}
