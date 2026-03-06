<?php
class Form_rtps_send_sign_evicertia_test_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct(){}

    public function send($json, $cia_code)
    {
        $parameters = json_decode($json, true);

        $curl_envio = new \Curl\Curl();
        $curl_envio->setHeader('Content-Type', 'application/json');

        if ($cia_code == 54) {
            $curl_envio->setHeader('Authorization', 'Basic ' . $this->config->item('evicertia_api_key_54'));
        } else {
            $curl_envio->setHeader('Authorization', 'Basic ' . $this->config->item('evicertia_api_key'));
        }

        $curl_envio->post($this->config->item('evicertia_api_url') . 'EviSign/Submit', $parameters);

        if ($curl_envio->error) {
            return $curl_envio->errorMessage;
        }

        return true;
    }
}
