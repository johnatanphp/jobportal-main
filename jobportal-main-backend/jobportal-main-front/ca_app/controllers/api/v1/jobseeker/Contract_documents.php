<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Contract_documents extends REST_Controller
{
    public function search_get()
    {   
        $this->load->library('Api_services/Api_v1/Candidate/Candidate_hiring_documents_service');
        $this->response(
            $this->candidate_hiring_documents_service->exec($this->get()), 
            self::HTTP_OK
        );
    }
}
