<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Searching extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    { 
        $this->load->library(
            'Api_services/Api_v2/Screening/Screening_list_lib',
        );

        $this->response(
            $this->screening_list_lib->list($this->get()),
            self::HTTP_OK
        );
    }

    public function create_post()
    { 
        $this->load->library(
            'Api_services/Api_v2/Screening/Screening_create_lib',
        );

        $this->response(
            $this->screening_create_lib->exec($this->post()),
            self::HTTP_OK
        );
    }
}
