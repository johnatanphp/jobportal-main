<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Processes extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Staff_request_process_list_lib',
            null, 
            'Staff_request_process_list_lib'
        );

        $this->response(
            $this->Staff_request_process_list_lib->list($this->get()), 
            self::HTTP_OK
        );
    }
}
