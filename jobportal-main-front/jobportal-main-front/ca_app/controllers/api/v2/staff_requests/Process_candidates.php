<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Process_candidates extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_candidates_list_lib',
            null, 
            'Process_candidates_list_lib'
        );

        $this->response(
            $this->Process_candidates_list_lib->list($this->get()), 
            self::HTTP_OK
        );
    }

    public function add_post()
    { 
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_candidates_add_lib',
        );
        
        list($is_success, $message) = $this->process_candidates_add_lib->validate($this->post());

        if (!$is_success) {
            $this->response(
                apiv2_response(false, $message),
                self::HTTP_BAD_REQUEST
            );
        }
        
         $response = $this->process_candidates_add_lib->add($this->post());
         
        if ($response['status'] !== true) {
            $this->response($response, self::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        $this->response($response, self::HTTP_OK);
    }
    
    public function move_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_candidates_move_lib',
            null, 
            'Process_candidates_move_lib'
        ); 

        $this->response(
            $this->Process_candidates_move_lib->move($this->post()), 
            self::HTTP_OK
        );
    }
    
    public function send_email_post()
    {
        show_404();
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_candidates_send_email_lib',
            null, 
            'Process_candidates_send_email_lib'
        ); 

        $this->response(
            $this->Process_candidates_send_email_lib->send($this->post()), 
            self::HTTP_OK
        );
    }

    public function send_whatsapp_post()
    {
        show_404();
        
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_candidates_send_whatsapp_lib',
            null, 
            'Process_candidates_send_whatsapp_lib'
        ); 

        $this->response(
            $this->Process_candidates_send_whatsapp_lib->send($this->post()), 
            self::HTTP_OK
        );
    }

    public function processes_get($candidate_id = 0)
    { 
        $this->load->library(
            'Api_services/Api_v2/Process_candidates/Process_candidates_processes_lib',
        ); 

        $get_data = $this->get();

        $this->response(
            $this->process_candidates_processes_lib->exec(array_merge(['candidate_id' => $candidate_id], $get_data)), 
            self::HTTP_OK
        );       
    }
}
