<?php
class Hrm_api_notificacion_cambio_legajo_lib 
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function notificar($parameters)
    {
        $curl_envio = new \Curl\Curl();
        $curl_envio->setHeader('Content-Type', 'application/json');
        $parameters['api_key'] = $this->config->item('hrm_api_key');

        $curl_envio->post($this->config->item('hrm_api_url') . '/notificacion/cambio_legajo', $parameters);

        if ($curl_envio->error) {		
            return false;
        }
        
        return true;
    }
}
