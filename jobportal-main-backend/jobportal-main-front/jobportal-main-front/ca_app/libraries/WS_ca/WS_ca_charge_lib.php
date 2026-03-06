<?php
class WS_ca_charge_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function sync($job_layout_id = null) 
    {
        if (!$this->config->item('ca_api_enabled')) {
            return true;
        }

        $this->load->model('Job_layout');

        $job_layout = $this->Job_layout->find($job_layout_id);

        if (!$job_layout) {
            return false;
        }

        if (!$job_layout->code_integration) {
            return false;
        }

        //Consultoras a registar / actualizar
        $cias_codes = ['01','02','03','05','16','17','22'];

        foreach ($cias_codes as $no_cia) {
            $this->send_charge($job_layout, $no_cia);
        }

        return true;
    }

    public function send_charge($job_layout, $no_cia)
    { 
        $url = $this->config->item('ca_api_url') . "/Personal/RegistrarCargos";

        $headers = [
            "Content-Type: application/json"
        ];
        
        $charge_details[] = [
            "Cod_Cargo" => $no_cia . $job_layout->code_integration,
            "Cargo" => $job_layout->job_title,
            "Cod_Alterno" => "-1",
            "Estado" => "S",
            "Nivel" => "",
            "Codigo_Cargo_Padre" => "",
            "Funciones_Principales" => "",
            "Funciones_Secundarias" => "",
            "NumDocIdent_Encargado" => "",
            "Codigo_Integracion" => "",
            "Valor_Minimo" => $job_layout->basic_minimum ? $job_layout->basic_minimum : '0',
            "Valor_Maximo" => $job_layout->basic_maximum ? $job_layout->basic_maximum : '0',
            "Monto_03" => "0",
            "Monto_04" => "0",
            "Monto_05" => "0",
            "Cadena_01" => "",
            "Cadena_02" => ""
        ];
     
        $parameters = [
            'database' => $this->config->item('ca_api_database'),
            'Cod_Usuario' => $this->config->item('ca_api_cod_user'),
            'cod_empresa' => get_consultant_code($no_cia),
            'Detalle_Cargos' => $charge_details
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
			CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->item('ca_api_auth_user') . ":" . $this->config->item('ca_api_auth_password'),
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		$response = curl_exec($ch);
      
        curl_close($ch);
        if ($response === false) {    
            return false;
        }

        $charge_response = json_decode($response);

        if (isset($charge_response->Success) && $charge_response->Success) {
            return true;
        }

        return false;
    }
}
