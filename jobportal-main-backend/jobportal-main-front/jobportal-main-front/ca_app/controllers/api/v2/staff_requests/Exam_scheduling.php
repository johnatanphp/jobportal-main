<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Exam_scheduling extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Exam_scheduling/Exam_scheduling_list_service'
        );

        $this->response(
            $this->exam_scheduling_list_service->exec($this->get()), 
            self::HTTP_OK
        );
    }
    
    public function create_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Exam_scheduling/Exam_scheduling_create_service'
        ); 

        list($is_success, $message) = $this->exam_scheduling_create_service->validate($this->post());

        if (!$is_success) {
            $this->response(
                apiv2_response(
                    false, 
                    $message,
                ),
                self::HTTP_BAD_REQUEST
            );
        }

        $this->response(
            $this->exam_scheduling_create_service->exec($this->post()), 
            self::HTTP_OK
        );
    }

    public function update_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Exam_scheduling/Exam_scheduling_update_service'
        ); 

        list($is_success, $message) = $this->exam_scheduling_update_service->validate($this->post());

        if (!$is_success) {
            $this->response(
                apiv2_response(
                    false, 
                    $message,
                ),
                self::HTTP_BAD_REQUEST
            );
        }

        $this->response(
            $this->exam_scheduling_update_service->exec($this->post()), 
            self::HTTP_OK
        );
    }

    public function cancel_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Exam_scheduling/Exam_scheduling_cancel_service'
        ); 

        $this->response(
            $this->exam_scheduling_cancel_service->exec($this->post()), 
            self::HTTP_OK
        );
    }

    public function send_sso_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Exam_scheduling/Exam_scheduling_notify_sso_service'
        ); 

        $this->response(
            $this->exam_scheduling_notify_sso_service->exec($this->post()), 
            self::HTTP_OK
        );
    }
}
