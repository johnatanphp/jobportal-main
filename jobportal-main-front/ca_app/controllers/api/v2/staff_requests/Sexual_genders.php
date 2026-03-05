<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Sexual_genders extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $response_data = [
            ['id' => 'female', 'name' => 'Femenino'],
            ['id' => 'male', 'name' => 'Masculino'],
            ['id' => 'both', 'name' => 'Ambos']
        ];
        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
