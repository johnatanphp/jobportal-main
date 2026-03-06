<?php
class Recaptcha {

	private $url_site_verify = 'https://www.google.com/recaptcha/api/siteverify';

	public function verify($recaptcha_response, $key_secret = '', $ip_user = null)
	{
		$params = array(
			'secret' => $key_secret, 
			'response' => $recaptcha_response,
		);

		if (!is_null($ip_user)) {
			$params['remoteip'] = $ip_user;
		}
	
		$options = array(
			CURLOPT_URL => $this->url_site_verify,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false
		);

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		
		$response = json_decode(curl_exec($ch), true);
		
		curl_close($ch);

		return $response;
	}
}
