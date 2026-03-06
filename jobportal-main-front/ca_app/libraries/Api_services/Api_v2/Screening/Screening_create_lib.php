<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening_create_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($input_data)
    {
        list($is_success, $message) = $this->validate($input_data);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }

        return $this->create($input_data);
    }

    public function create($params)
    {
        $this->load->library('Screening/Screening_jobseeker_search_lib');

        $created_by_id = $this->session_employer_lib->get_data('user_id');
    
        $document_number = $params['identification_document_number'];

        $this->db->select([
            'ID as id'
        ]);
        $this->db->from('tbl_job_seekers');
        $this->db->where('document_number', $document_number);
        $jobseeker = $this->db->get()->row();
        
        $response = $this->screening_jobseeker_search_lib->create([
            'document_number' => $document_number,
            'type' => 1, //Screening basico
            'seeker_id' => $jobseeker ? $jobseeker->id : null,
            'cost_center' => $params['cost_center_id'],
            'job_title' => $params['job_title'],
            'type_expense' => $params['expense_type_id'],
            'created_by_id' => $created_by_id
        ]);
    
        if ($response['status'] === false) {
            return apiv2_response(false, $response['message']);
        }

        $id = $response['data']['id'] ?? false;

        $this->load->library('Api_services/Api_v2/Screening/Screening_list_lib');
        
        $screening_response = $this->screening_list_lib->list([
            'id' => $id,
            'identification_document_number' => $document_number
        ]);

        $response_data = [];

        if ($screening_response['status'] == true) {
            $response_data = $screening_response['data'][0] ?? [];
        }
      
       return apiv2_response(true, 'OK', $response_data);
    }

    private function validate($input_data)
    {
        $input_data = count($input_data) == 0 ? ['0'] : $input_data;

        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'required');
        $this->form_validation->set_rules('cost_center_id', 'cost_center_id', 'required');
        $this->form_validation->set_rules('job_title', 'job_title', 'required');
        $this->form_validation->set_rules('expense_type_id', 'expense_type_id', 'required');
        $this->form_validation->set_rules('process_id', 'process_id', 'trim');
       
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        return [true, 'OK'];
    }
}
