<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Responsible_assignments extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get($request_id = 0)
    {
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Responsible_assignment_list_lib'
        );
        
        $response = $this->responsible_assignment_list_lib->list($request_id,  $this->get());

        $this->response($response, parent::HTTP_OK);
    }

    public function update_post($request_id = 0)
    {
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Responsible_assignment_update_lib'
        );
        
        $response = $this->responsible_assignment_update_lib->update($request_id,  $this->post());

        $this->response($response, parent::HTTP_OK);
    }
}
