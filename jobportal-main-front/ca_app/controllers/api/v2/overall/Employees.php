<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Employees extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_get()
    {
        $params = $this->get();
        
        $this->form_validation->set_data(count($params) > 0 ? $params : [false]);
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'required|trim|min_length[7]');
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            $this->response(
                apiv2_response(false, current($message_error)),
                self::HTTP_BAD_REQUEST
            );
        }
        
        $url = $this->config->item('hrm_api2_url') . '/trabajador/buscar';
        
        $document_number = $params['identification_document_number'];
        
        $parameters = [
            'documento_identidad_numero' => $document_number
        ];

		$curl_options = [
			CURLOPT_URL => $url . "?" . http_build_query($parameters),
			CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_TIMEOUT => 20,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
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
        
        $response = json_decode($response);
           
        if ($response->error == 1) {
            $this->response(
                apiv2_response(false, 'Error al consultar API - HRM API'),
                self::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $employees = $response->data ?? [];
        
        $data = [];
        
        foreach ($employees as $employee_row) {
            $data[] = [
                'identification_document_number' => $employee_row->documento_identidad_numero,
                'first_name' => trim($employee_row->primer_nombre),
                'last_name' => trim($employee_row->apellido_paterno . ' ' . (string)$employee_row->apellido_materno),
            ];
        }
        
        $this->response(
            apiv2_response(true, 'OK', $data),
            200
        );
    }
}
