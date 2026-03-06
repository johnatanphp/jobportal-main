<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_reniec_search_lib
{
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

        $this->form_validation->set_rules('identification_document_type_id', 'identification_document_type_id', 'trim|required|integer|in_list[1,4]');
        $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'trim|required');

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
        $this->load->library(
            'Jobseeker/Jobseeker_search_info_lib',
            null, 
            'Jobseeker_search_info_lib'
        ); 

        $candidate_data = [];
        $candidate_info = $this->Jobseeker_search_info_lib->search($params['identification_document_type_id'], $params['identification_document_number']);

        if ($candidate_info && isset($candidate_info->document_number) && $candidate_info->document_number) {
            $candidate_data = [
                'identification_document_type_id' => isset($candidate_info->document_type) ? (int)$candidate_info->document_type : null,
                'identification_document_type_name' => isset($candidate_info->document_type_name) ? (string)$candidate_info->document_type_name : null,
                'identification_document_number' => isset($candidate_info->document_number) ? (string)$candidate_info->document_number : null,
                'first_name' => isset($candidate_info->first_name) ? (string)$candidate_info->first_name : null,
                'paternal_last_name' => isset($candidate_info->paternal_last_name) ? (string)$candidate_info->paternal_last_name : null,
                'maternal_last_name' => isset($candidate_info->maternal_last_name) ? (string)$candidate_info->maternal_last_name : null,
                'birth_date' => isset($candidate_info->dob) ? (string)$candidate_info->dob : null,
                'gender_id' => isset($candidate_info->gender) ? (int)$candidate_info->gender : null, 
                'gender_name' => isset($candidate_info->gender_name) ? (string)$candidate_info->gender_name : null,
                'civil_status_id' => isset($candidate_info->civil_status) ? (int)$candidate_info->civil_status : null, 
                'civil_status_name' => isset($candidate_info->civil_status_name) ? (string)$candidate_info->civil_status_name : null,
                //'country_id' => 56,
                //'country_name' => 'Perú',
                'department_id' => isset($candidate_info->department_id) ? (string)$candidate_info->department_id : null,
                'department_name' => isset($candidate_info->department_name) ? (string)$candidate_info->department_name : null,
                'province_id' => isset($candidate_info->province_id) ? (string)$candidate_info->province_id : null,
                'province_name' => isset($candidate_info->province_name) ? (string)$candidate_info->province_name : null,
                'district_id' => isset($candidate_info->district_id) ? (string)$candidate_info->district_id : null,
                'district_name' => isset($candidate_info->district_name) ? (string)$candidate_info->district_name : null,
                'present_address' => isset($candidate_info->present_address) ? (string)$candidate_info->present_address : null,
                //'photo' => isset($candidate_info->photo) ? (string)$candidate_info->photo : null
            ];
        }
       
        return [
            'status' => true,
            'message' => 'Ok',
            'data' => $candidate_data
        ];  
    }
}
