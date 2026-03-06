<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Exam_scheduling_exam_types extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Exam_scheduling/Exam_scheduling_exam_types_list_service'
        );

        $this->response(
            $this->exam_scheduling_exam_types_list_service->exec($this->get()), 
            self::HTTP_OK
        );
    }
}
