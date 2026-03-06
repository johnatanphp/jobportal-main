<?php
class WS_sap_charge_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function add($job_layout_id) 
    {
        $this->load->model('Job_layout');
        
        $job_layout = $this->Job_layout->find($job_layout_id);

        if (!$job_layout) {
            return false;
        }

        if (!$job_layout->code_integration) {
            return false;
        }

        $this->load->library('WS_sap/WS_sap_api_token_lib', null, 'WS_sap_api_token_lib');
        $sap_token = $this->WS_sap_api_token_lib->generate($job_layout->company_id);

        if (!$sap_token) {
            return false;
        }

        $url = $sap_token->api_url . "/v1/EXX_ADCT_CRGO";
        $token = $sap_token->token;

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token,
            "Cookie: B1SESSION=" . $token
        ];

        $parameters = [
            'Code' => $job_layout->code_integration,
            'Name' => $job_layout->job_title,
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
            return false;
        }
        
        $json_response = json_decode($response);

        if (!isset($json_response->error)) {
            return true;
        }

        return false;
    }

    public function update($job_layout_id) 
    {
        $this->load->model('Job_layout');
        $job_layout = $this->Job_layout->find($job_layout_id);

        if (!$job_layout) {
            return false;
        }

        if (!$job_layout->code_integration) {
            return false;
        }

        $this->load->library('WS_sap/WS_sap_api_token_lib', null, 'WS_sap_api_token_lib');
        $sap_token = $this->WS_sap_api_token_lib->generate($job_layout->company_id);

        if (!$sap_token) {
            return false;
        }

        $url = $sap_token->api_url . "/v1/EXX_ADCT_CRGO('" . $job_layout->code_integration . "')";
        $token = $sap_token->token;
        
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token,
            "Cookie: B1SESSION=" . $token
        ];

        $parameters = [
            'Name' => $job_layout->job_title,
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'PATCH',
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
            return false;
        }
        
        $json_response = json_decode($response);
        
        if (!isset($json_response->error)) {
            return true;
        }

        return false;
    }
}
