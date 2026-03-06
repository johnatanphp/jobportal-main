<?php
class Hrm_api_employee_info_search
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function run($document_number)
    {
        $curl_envio = new \Curl\Curl();
        $curl_envio->setHeader('Content-Type', 'application/json');
        $parameters['api_key'] = $this->config->item('hrm_api_key');
        $parameters['nro_documento'] = $document_number;

        $curl_envio->get($this->config->item('hrm_api_url') . '/trabajador/info', $parameters);

        if ($curl_envio->error) {		
            return false;
        }

        $response =  $curl_envio->response;

        if (!$response || !isset($response->error) || $response->error == 1) {
            return false;
        }

        return $response->data; 
    }
}
