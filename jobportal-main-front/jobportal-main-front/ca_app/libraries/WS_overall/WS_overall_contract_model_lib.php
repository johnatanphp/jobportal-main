<?php
class WS_overall_contract_model_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($params)
    {
        $results = $this->search($params);
        $list = [];

        foreach ($results as $row) {
            $projects = isset($row->Proyectos) ? $row->Proyectos : [];

            foreach ($projects as $row_project) {
                foreach ($row_project->Detalle as $row_detail) {
                    
                    $obj = new stdClass();
                    $obj->contract_model_code = $row_detail->Tipo_Contrato;
                    $obj->contract_model_name = $row_detail->Modelo_Contrato;

                    $list[$row_detail->Tipo_Contrato] = $obj;
                }
            }
        }

        return $list;
    }

    private function search($params) 
    {
        if (!$this->config->item('ca_api_enabled')) {
            return [];
        }

        $no_cia = $params['no_cia'];
        $client_code = isset($params['client_code']) ? $params['client_code'] : '%';
        $charge_code = isset($params['charge_code']) ? $params['charge_code'] : '%';

        $url = $this->config->item('ca_api_url') . "/Personal/Listar_ModelosContratos";

        $parameters = [
            'database' => $this->config->item('ca_api_database'),
            'usuario' => $this->config->item('ca_api_cod_user'),
            'empresa' => get_consultant_code($no_cia),
            'proyecto' => '%',
            'cliente' => '%',
            'cargo' => $charge_code,
            'estado' => 'A'
        ];

		$curl_options = [
			CURLOPT_URL => $url . "?" . http_build_query($parameters),
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->item('ca_api_auth_user') . ":" . $this->config->item('ca_api_auth_password'),
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

        //Registrar postulante en sistema de nomina
		$response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {    
            return [];
        }

        return json_decode($response);
    }
}
