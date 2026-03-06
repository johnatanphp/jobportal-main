<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employer_profile_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($input_data = [])
    {
        list($is_success, $message) = $this->validate($input_data);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }

        return $this->get_response_data($input_data);
    }

    private function validate($input_data)
    {
        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        // $this->form_validation->set_rules('request_id', 'request_id', 'required|integer|greater_than[0]');
       
        // $this->form_validation->set_message('required', 'El campo %s es requerido');

        // if ($this->form_validation->run() === FALSE) {
        //     $message_error = $this->form_validation->error_array();
        //     return [false, current($message_error)];
        // }

        return [true, 'OK'];
    }

    private function get_response_data($input_data)
    {
        $user_id = $this->session_employer_lib->get_data('user_id');
        $employer = $this->Employer->find($user_id);

        $this->db->select([
            'employers.ID AS id',
            'employers.first_name AS first_name',
            'employers.last_name AS last_name',
            'employers.email AS email',
            'employers.sts AS sts',
            'companies.ID AS company_id',
            'companies.company_name AS company_name'
        ]);
        $this->db->from('tbl_employers employers');
        $this->db->join('tbl_companies companies', 'employers.company_ID=companies.ID');
        $this->db->where('employers.ID', $employer->ID);   
        $employers = $this->db->get()->result();
        $response_data = [];

        foreach ($employers as $row_res) {
            $response_data = [
                'id' => $row_res->id,
                'email' => (string)$row_res->email,
                'first_name' => (string)$row_res->first_name,
                'last_name' => (string)$row_res->last_name,
                'status_id' => (string)$row_res->sts,
                'company_id' => $row_res->company_id,
                'company_name' => (string)$row_res->company_name  
            ];
        }

        return apiv2_response(true, 'OK', $response_data);
    }
}
