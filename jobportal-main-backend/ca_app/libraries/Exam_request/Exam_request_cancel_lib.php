<?php 
class Exam_request_cancel_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function run($ref_ids)
    {
        $api_url = $this->config->item('hrm_api_url') . '/programacion_examen/cancelar';
        //$api_url = 'http://172.20.0.1:8072/hrm/public/index.php/api/programacion_examen/cancelar';

        $data = [
            'api_key' => $this->config->item('hrm_api_key'),
            'ref_id' => $ref_ids
        ];

        $curl_envio = new \Curl\Curl();
        $curl_envio->setHeader('Content-Type', 'application/json');
        $curl_envio->post($api_url, $data);

        $response =  $curl_envio->response;

        if (!$response) {
            return;
        }

        if (!$response->success) {
            return $response->message;
        } 

        return $response->data->programaciones;
    }
}
