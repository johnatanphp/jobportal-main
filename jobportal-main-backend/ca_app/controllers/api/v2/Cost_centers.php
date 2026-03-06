<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Cost_centers extends Api_v2_Controller
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

        //Filtar centro de costos por unidad de negocio
        if (isset($params['business_unit_id'])) {
            $filters['business_unit_code'] = trim($params['business_unit_id']);
        }
        
        $results = $this->Employer->get_permission_cost_centers($filters);

        //dd($results);

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->cost_center_code,
                'name' => $row->cost_center_code,
                'active' => 1
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
