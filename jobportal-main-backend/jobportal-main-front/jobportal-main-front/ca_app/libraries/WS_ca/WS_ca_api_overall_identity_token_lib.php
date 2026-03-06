<?php

class WS_ca_api_overall_identity_token_lib 
{   
    public function __construct(){}

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function generate()
    {
        $headers = [
			"Content-Type: application/json"
        ];
		
		$post = [
			'username' => $this->config->item('ca_api_base_auth_user'),
			'password' => $this->config->item('ca_api_base_auth_password')
		];

		$url = $this->config->item('ca_api_base_url') . "/token/overall/identity";
       
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($post),
			CURLOPT_HTTPHEADER => $headers
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);

        if (!$response || $response == 'Acceso denegado.') {
            return null;
        }

        return $response;
    }
}
