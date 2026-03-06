<?php
class WS_overall_charge_lib
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

        $url = $this->config->item('hrm_api2_url') . "/inserta-puesto-prc";
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->config->item('hrm_api2_bearer_token')
        ];

        $parameters = [
            "pdesc_puesto" => $job_layout->job_title,
            "pabrev_puesto" => $job_layout->code,
            "pin_estd" => "A",
            "pcod_sunat" => $job_layout->sunat_code ? $job_layout->sunat_code : '',
            "pbasico_min" => $job_layout->basic_minimum,
            "pbasico_max" => $job_layout->basic_maximum,
            "palimento_min" => 0,
            "palimento_max" => 0,
            "pmovilidad_min" => 0,
            "pmovilidad_max" => 0,
            "pbono_comision_min" => 0,
            "pbono_comision_max" => 0,
            "pcodigo_postal" => $job_layout->code_integration
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
}
