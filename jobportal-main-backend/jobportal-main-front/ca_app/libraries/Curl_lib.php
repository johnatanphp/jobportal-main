<?php
class Curl_lib
{
    public function __get($var) {
        return get_instance()->$var;
    }

    public function exec($url, $method, $params)
    {
    	$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => http_build_query($params),
			CURLOPT_TIMEOUT => 25 //Segundos
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
    }
}
