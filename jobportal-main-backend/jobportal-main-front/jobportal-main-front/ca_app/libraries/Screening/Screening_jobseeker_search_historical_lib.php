<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Screening_jobseeker_search_historical_lib 
{	
	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    
    public function search($document_number)
    {     
        $response = $this->search_by([
            'user' => $this->config->item('screening_api_auth_1_username'),
            'password' => $this->config->item('screening_api_auth_1_password'),
            'document_number' => $document_number
        ]);
        
        $datalist = [];

        if (isset($response->datalist) && count($response->datalist) > 0) {
            $datalist = $response->datalist;
        }   

        $response = $this->search_by([
            'user' => $this->config->item('screening_api_auth_2_username'),
            'password' => $this->config->item('screening_api_auth_2_password'),
            'document_number' => $document_number
        ]);

        if (isset($response->datalist) && count($response->datalist) > 0) {
            $datalist+= $response->datalist;
        }   

        $response = $this->search_by([
            'user' => $this->config->item('screening_api_auth_3_username'),
            'password' => $this->config->item('screening_api_auth_3_password'),
            'document_number' => $document_number
        ]);

        if (isset($response->datalist) && count($response->datalist) > 0) {
            $datalist+= $response->datalist;
        } 

        return $this->build($datalist);
    }

    public function search_by($params)
    {
        $document_number = $params['document_number'];
		
        if (trim($document_number) == '') {
            return false;
        }

        $token = $this->getToken($params['user'], $params['password']);

		$headers = [
			"Authorization: Bearer " . $token,
			"Content-Type: application/json"
        ];

		$parameters = [
			'data' => [
                'documentNumber' => $document_number
            ]
		];

        $url = $this->config->item('screening_api_url') . "/shc/gshbdws";
		
        $options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_TIMEOUT => 10 //Segundos
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);

		return @json_decode($response);
    }

    public function getToken($user, $password)
	{
		$headers = [
			"Content-Type: application/json"
        ];

		$post = [
			'username' => $user,
			'password' => $password
		];

        $url = $this->config->item('screening_api_url') . "/lc/vlex";

		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($post),
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_TIMEOUT => $this->config->item('screening_api_login_timeout') //Segundos
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response);

		return isset($response->sessionKey) ? $response->sessionKey : false;
	}

    private function build($list)
    {
        $std_list = [];

		foreach ($list as $row) {
			$std = new stdClass();

            $screening_url = '';
            $screening_type = '';

            if (isset($row->vipReportUrl)) {
                $screening_url = $row->vipReportUrl;
                $screening_type = 'Integral';
            }

            if (isset($row->basicPlusReportUrl)) {
                $screening_url = $row->basicPlusReportUrl;
                $screening_type = 'Básico';
            }

            if (empty($screening_url)) {
                continue;
            }

			$std->id = null;
			$std->type_name = $screening_type;
			$std->file_path = $screening_url;
			$std->created_at = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $row->consultDate)));
			$std->origin = 'historical';
            $std->attached_report_url = isset($row->attachReport2Url) ? $row->attachReport2Url : null;
            $std->cost_center = $row->costCenter;
            $std->request_user = $row->user;
            $std->due_date = null;
            $std->remaining_days = null;

			$std_list[] = $std;
		}

        return $std_list;
    }
}
