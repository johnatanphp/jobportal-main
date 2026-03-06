<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Candidates extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
    }
    
    public function create_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Candidate/Candidate_create_candidate_lib',
            null, 
            'Candidate_create_candidate_lib'
        ); 

        $this->response(
            $this->Candidate_create_candidate_lib->create($this->post()), 
            self::HTTP_OK
        );
    }

    public function search_get()
    { 
        $this->load->library(
            'Api_services/Api_v2/Candidate/Candidate_search_lib',
            null, 
            'Candidate_search_lib'
        ); 

        $this->response(
            $this->Candidate_search_lib->search($this->get()), 
            self::HTTP_OK
        );
    }

    public function reniec_search_get()
    { 
        $this->load->library(
            'Api_services/Api_v2/Candidate/Candidate_reniec_search_lib',
            null, 
            'Candidate_reniec_search_lib'
        ); 

        $this->response(
            $this->Candidate_reniec_search_lib->search($this->get()), 
            self::HTTP_OK
        );
    }

    public function overall_work_history_get()
    {
        $this->load->library('Api_services/Api_v2/Candidate/candidate_overall_work_history_list_service'); 

        $this->response(
            $this->candidate_overall_work_history_list_service->exec($this->get()), 
            self::HTTP_OK
        );
    }
}
