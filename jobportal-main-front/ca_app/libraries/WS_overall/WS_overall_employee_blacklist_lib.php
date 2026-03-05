<?php
class WS_overall_employee_blacklist_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($document_numbers = []) 
    {      
        $url = $this->config->item('hrm_api_url') . '/trabajador/buscar_lista_negra';

        $parameters = [
            'dni' => join(',', $document_numbers)
        ];

		$curl_options = [
			CURLOPT_URL => $url . "?" . http_build_query($parameters),
			CURLOPT_CUSTOMREQUEST => 'GET',
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

        $results = $response->data ?? [];
        
        $result_blacklist = [];

        foreach ($results as $row_blacklist) {
            $obj_blacklist = new stdClass();
            $obj_blacklist->blacklist = 1;
            $obj_blacklist->blacklist_observation = $row_blacklist->observacion;
            
            $result_blacklist[$row_blacklist->num_doc_iden] = $obj_blacklist;
        }

        return $result_blacklist;
    }
}
