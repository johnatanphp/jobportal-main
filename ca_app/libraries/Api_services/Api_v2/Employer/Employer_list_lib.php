<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employer_list_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($input_data)
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
            'employers.email AS email',
            'employers.sts AS sts',
            'companies.ID AS company_id',
            'companies.company_name AS company_name'
        ]);
        $this->db->from('tbl_employers employers');
        $this->db->join('tbl_companies companies', 'employers.company_ID=companies.ID');
        $this->db->join('tbl_employer_profiles emp_profiles', 'emp_profiles.user_id=employers.ID');
        $this->db->where('employers.company_ID', $employer->company_ID);

        if (isset($input_data['permission_responsible_consultant_id']) || isset($input_data['permission_responsible_client_id'])) {
            $this->db->join(
                'tbl_employer_responsibles_clients_permissions emp_responsibles_permissions', 
                'emp_responsibles_permissions.employer_id=employers.ID AND emp_responsibles_permissions.company_id=employers.company_ID'
            );
        }

        $this->db->where_in('emp_profiles.profile_id', [1, 2, 3]);

        if (isset($input_data['id'])) {
            $this->db->where('employers.id', trim($input_data['id']));
        }

        if (isset($input_data['email'])) {
            $this->db->where('employers.email', trim($input_data['email']));
        }

        if (isset($input_data['status'])) {
            $this->db->where('employers.sts', trim($input_data['status']));
        }

        if (isset($input_data['profile_id'])) {
            $this->db->where('emp_profiles.profile_id', trim($input_data['profile_id']));
        }

        if (isset($input_data['permission_responsible_consultant_id'])) {
            $this->db->where('emp_responsibles_permissions.cia_code', trim($input_data['permission_responsible_consultant_id']));
        }

        if (isset($input_data['permission_responsible_client_id'])) {
            $this->db->where('emp_responsibles_permissions.client_code', trim($input_data['permission_responsible_client_id']));
        }
    
        $query_search = trim($input_data['query'] ?? '');

        if ($query_search != '') {
            $this->db->group_start();
            $this->db->like('employers.first_name', $query_search);
            $this->db->or_like('employers.email', $query_search);
            $this->db->or_like('employers.ID', $query_search);
            $this->db->group_end();
        }

        $this->db->group_by('employers.ID');
       
        $employers = $this->db->get()->result();
        $response_data = [];

        foreach ($employers as $row_res) {
            $response_data[] = [
                'id' => (int)$row_res->id,
                'email' => (string)$row_res->email,
                'first_name' => (string)$row_res->first_name,
                'status_id' => (string)$row_res->sts,
                'company_id' => (int)$row_res->company_id,
                'company_name' => (string)$row_res->company_name  
            ];
        }

        return apiv2_response(true, 'OK', $response_data);
    }
}
