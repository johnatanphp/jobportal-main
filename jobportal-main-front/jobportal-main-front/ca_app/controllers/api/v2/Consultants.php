<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Consultants extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $employer_id = $this->session_employer_lib->get_data('user_id');
        $params = $this->get();
            
        $filters = [
            'permission_employer_id' => $employer_id
        ];
        
        if (isset($params['consultant_id'])) {
            $filters['code'] = $params['consultant_id'];
        }
            
        $results = $this->Employer->get_permission_consultants($filters);

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->consultant_code,
                'name' => $row->consultant_name,
                'active' => 1
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}