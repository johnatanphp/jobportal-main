<?php
class Company_search_info_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
    public function search($ruc)
    {
        $ruc = trim($ruc);
        
        if (empty($ruc)) {
            return [];
        }   

        $token = $this->config->item('dniruc_api_token');
        $url = $this->config->item('dniruc_api_url') . "/search/ruc/$ruc/$token";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            return [];
        }

        $response = @json_decode($response);

        if (!$response->success) {
            return [];
        }
    
        return $this->buil_response($response->data);
    }

    public function buil_response($data)
    {
        $company_data = [
            'ruc' => isset($data->ruc) ? $data->ruc : null,
            'name' => isset($data->nombre_comercial) ? $data->nombre_comercial : null,
            'address' => isset($data->direccion) ? $data->direccion : null 
        ];

        return json_decode(json_encode($company_data));
    }
}
