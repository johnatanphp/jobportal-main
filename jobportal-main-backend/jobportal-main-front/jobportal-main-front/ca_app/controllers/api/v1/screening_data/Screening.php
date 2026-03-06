<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Screening extends REST_Controller
{
    public function search_get()
    {
        $input = $this->input->get();

        $this->load->library(
            'Api_services/Api_v1/Screening/Screening_list_lib',
        );

        list($is_success, $message) = $this->screening_list_lib->validate($this->get());

        if (!$is_success) {
            $this->response(
                apiv2_response(false, $message),
                self::HTTP_BAD_REQUEST
            );
        }

        $this->response(
            $this->screening_list_lib->list($this->get()),
            self::HTTP_OK
        );
    }
}