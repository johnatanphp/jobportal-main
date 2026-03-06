<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Jobs extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function post_job_post($request_id = 0)
    { 
        if (empty($request_id)) {
            api_show_404();
        }   

        $params = $this->post();
        $params['request_id'] = $request_id;

        $this->load->library(
            'Api_services/Api_v2/Staff_request/Staff_request_job_post_lib',
            null, 
            'Staff_request_job_post_lib'
        );

        $this->response(
            $this->Staff_request_job_post_lib->post($params),
            self::HTTP_OK
        );
    }
}
