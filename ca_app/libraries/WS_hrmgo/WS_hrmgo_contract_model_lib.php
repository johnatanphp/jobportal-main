<?php
class WS_hrmgo_contract_model_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($params)
    {
        $results = $this->search($params);
        $items = [];

        foreach ($results as $row) {
            $obj = new stdClass();
           
            $obj->code = $row->codification;
            $obj->name = $row->name;
           
            $items[] = $obj;
        }

        return $items;
    }

    private function search($params) 
    {
        $this->load->library('WS_hrmgo/WS_hrmgo_api_token_lib');

        $hrm_api = $this->ws_hrmgo_api_token_lib->get_token($params['company_id']);

        if (!$hrm_api) {
            return [];
        }

        $api_url = $hrm_api->api_url . "/contractsTemplates/contracts/models?page=1&limit=1500";

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $hrm_api->token
        ];

		$curl_options = [
			CURLOPT_URL => $api_url,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 25,
			CURLOPT_HTTPHEADER => $headers,
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
