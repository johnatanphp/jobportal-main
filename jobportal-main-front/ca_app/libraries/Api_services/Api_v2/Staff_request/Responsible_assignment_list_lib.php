<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Responsible_assignment_list_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($request_id, $input_data)
    {
        $input_data['request_id'] = $request_id;

        list($is_success, $message) = $this->validate($input_data);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }

        return $this->get_response($input_data);
    }

    private function validate($input_data)
    {
        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('request_id', 'request_id', 'required|integer|greater_than[0]');
       
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        return [true, 'OK'];
    }

    private function get_response($input_data)
    {
        $request_id = $input_data['request_id'];

        $this->db->select([
            'employers.ID AS id',
            'employers.first_name AS first_name'
        ]);
        $this->db->from('tbl_staff_request_assigned_employers assigned_employers');
        $this->db->join('tbl_staff_requests staff_requests', 'staff_requests.ID=assigned_employers.request_ID');
        $this->db->join('tbl_employers employers', 'employers.ID=assigned_employers.employer_ID');
        $this->db->where('staff_requests.request_model_id', 4);
        $this->db->where('staff_requests.ID', $request_id);
        $this->db->group_by('employers.ID');
        
        $responsibles = $this->db->get()->result();
        $response_data = [];

        foreach ($responsibles as $row_res) {
            $response_data[] = [
                'id' => (int)$row_res->id,
                'first_name' => (string)$row_res->first_name
            ];
        }

        return apiv2_response(true, 'OK', $response_data);
    }
}
