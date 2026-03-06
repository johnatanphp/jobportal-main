<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Buscar extends REST_Controller 
{
    public function index_get()
    {
        $this->load->model('api/Api_jobseeker');
    
        $filters = [
            'di' => trim($this->get('di')),
            'di_tipo' => trim($this->get('di_tipo'))
        ];

        $this->response(array('data' => jobseeker_search($filters)), 200);
    }
}
