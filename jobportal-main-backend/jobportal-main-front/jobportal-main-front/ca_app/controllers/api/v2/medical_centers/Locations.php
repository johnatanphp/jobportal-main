<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Locations extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $url = $this->config->item('hrm_api2_url') . "/programacion_examen/centro_medico/sedes";
        
        $headers = [
            "Content-Type: application/json"
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
			//CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		$response = curl_exec($ch);
        
        curl_close($ch);
        if ($response === false) {
            $this->response(
                apiv2_response(false, 'Error al conectar API - HRM API'),
                self::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        $json_response = json_decode($response);

        $medical_centers = [];

        if ($json_response->error == 1) {
            $this->response(
                apiv2_response(false, 'Error al consultar API - HRM API'),
                self::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $medical_centers = $json_response->data;
        $data = [];

        foreach ($medical_centers as $center) {

            $center_ubigeo = str_split($center->sede_ubigeo_codigo, 2);
    
            $data[] = [
                'code' => (string)$center->sede_id,
                'name' => $center->sede_nombre,
                'address' => $center->sede_direccion,
                'country_id' => "56",
                'country_name' => "Perú",
                'department_id' => $center_ubigeo[0],
                'department_name' => $center->sede_departamento,
                'province_id' => $center_ubigeo[0] . $center_ubigeo[1],
                'province_name' => $center->sede_provincia,
                'district_id' => $center->sede_ubigeo_codigo,
                'district_name' => $center->sede_distrito,
                'medical_center' => [
                    'code' => $center->proveedor_codigo,
                    'name' => $center->proveedor_descripcion
                ]
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $data),
            self::HTTP_OK
        );
    }
}
