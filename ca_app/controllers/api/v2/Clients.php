<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Clients extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();
        $session_employer_id = $this->session_employer_lib->get_data('user_id');
                
        $filters = [
            'permission_employer_id' => $session_employer_id
        ];
        
        //Filtar clientes por id consultora
        if (isset($params['consultant_id'])) {
            $filters['consultant_code'] = trim($params['consultant_id']);
        }

        //Filtar clientes por id cliente
        if (isset($params['client_id'])) {
            $filters['client_code'] = trim($params['client_id']);
        }

        $results = $this->Employer->get_permission_clients($filters);

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->client_code,
                'name' => $row->client_name,
                'active' => 1
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
