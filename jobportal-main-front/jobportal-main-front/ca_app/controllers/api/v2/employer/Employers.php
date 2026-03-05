<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Employers extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Employer/Employer_list_lib'
        );
        
        $response = $this->employer_list_lib->list($this->get());
        
        $this->response($response, 200);
    }

    public function profile_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Employer/Employer_profile_lib'
        );
        
        $response = $this->employer_profile_lib->exec();
        
        $this->response($response, 200);
    }
}