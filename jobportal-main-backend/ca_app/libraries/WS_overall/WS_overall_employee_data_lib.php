<?php

class WS_overall_employee_data_lib 
{   
    public function __construct(){}

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function search($document_number = [])
    {
        if (!$this->config->item('eplani_api_enabled')) {
            return [];
        }

        $url = $this->config->item('hrm_api2_url') . '/trabajador/resumen_info';

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
            return [];
        }

        $response = json_decode($response);

        return $response->data ?? [];
    }
}
