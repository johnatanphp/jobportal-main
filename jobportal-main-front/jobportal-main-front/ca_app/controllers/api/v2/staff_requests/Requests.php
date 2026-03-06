<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Requests extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function create_post()
    {
        $this->load->library('Api_services/Api_v2/Staff_request/Staff_request_create_lib');

        list($is_success, $message) = $this->staff_request_create_lib->validate($this->post());

        if (!$is_success) {
            $this->response(
                apiv2_response(false, $message),
                self::HTTP_BAD_REQUEST
            );
        }

        $response = $this->staff_request_create_lib->create($this->post());

        if (!$response['status']) {
            $this->response(
                $response,
                self::HTTP_INTERNAL_SERVER_ERROR
            );
        }
       
        $this->response(
            $response,
            self::HTTP_OK
        );
    }

    public function list_get()
    {
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Staff_request_list_lib',
            null, 
            'Staff_request_list_lib'
        );

        $this->response(
            $this->Staff_request_list_lib->list($this->get()), 
            self::HTTP_OK
        );
    }

    public function processes_stages_get($request_id = 0)
    {
        if (empty($request_id)) {
            api_show_404();
        }

        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_stages_list_lib',
            null, 
            'Process_stages_list_lib'
        );

        $params['request_id'] = $request_id;

        $this->response(
            $this->Process_stages_list_lib->list($params), 
            self::HTTP_OK
        );
    }

    public function screening_stages_get()
    {
        $params = $this->get();
        $response_data = [];

        if (isset($params['its_screening']) && $params['its_screening'] == 1) {
            $this->db->select([
                'stage.id',
                'stage.name',
            ]);
            $this->db->from('tbl_recruitment_stages stage');
            $this->db->where('stage.active', true);
            $this->db->where('stage.its_screening', 1);

            $results = $this->db->get()->result();

            if (!empty($results)) {
                $response_data = array_map(function($row) {
                    return [
                        'id' => $row->id,
                        'name' => $row->name,
                    ];
                }, $results);
            }
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }

    public function start_post($request_id = 0)
    {
        if (empty($request_id)) {
            api_show_404();
        }
        
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Staff_request_start_lib',
            null, 
            'Staff_request_start_lib'
        );

        $params = $this->post();
        $params['request_id'] = $request_id;

        $this->response(
            $this->Staff_request_start_lib->start($params), 
            self::HTTP_OK
        );
    }
}
