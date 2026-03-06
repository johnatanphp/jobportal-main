<?php
class WS_sap_api_token_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function generate($company_id, $params = []) 
    {
        $this->load->model('Sap_api_user');

        if (!$this->config->item('sap_api_enabled')) {
            return null;
        }

        if (is_array($params) && count($params) > 0) {
            $api_url = $params['api_url'];
            $api_username = $params['api_username'];
            $api_password = $params['api_password'];
            $api_db = $params['api_db'];
        } else {

            $sap_api = $this->Sap_api_user->find(['company_id' => $company_id]);

            if (!$sap_api) {
                return null;
            }

            if ($sap_api->active != 1) {
                return null;
            }

            $api_url = $sap_api->api_url;
            $api_username = $sap_api->api_username;
            $api_password = $sap_api->api_password;
            $api_db = $sap_api->api_db;
        }

        $url = $api_url . "/v1/Login";

        $headers = [
            "Content-Type: application/json"
        ];

        $parameters = [
            'CompanyDB' => $api_db,
            'UserName' => $api_username,
            'Password' => $api_password
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
			CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		$response = curl_exec($ch);
        
        curl_close($ch);
        if ($response === false) {  
            return null;
        }
        
        $json_response = json_decode($response);

        if (isset($json_response->SessionId))  {
            $sap_api = new stdClass();
            $sap_api->api_url = $api_url;
            $sap_api->token = $json_response->SessionId;
            return $sap_api;
        }
    
        return null;
    }
}
