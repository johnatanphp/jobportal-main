<?php
class Whatsapp_jobseeker_send_request_documents_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($params)
    { 
        $mobile = format_mobile($params['mobile']);

        if (!$mobile) {
            return false;
        }

        $url = $this->config->item('hrm_api2_url') . '/whatsapp/employee/request-send-link/' . $mobile;

        $data = [
            'full_name' => $params['full_name'],
            'position' => $params['position'],
            'linkUploadDocument' => $params['linkUploadDocument'],
            'ccosto' => $params['ccosto']
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_TIMEOUT => 10 //Segundos
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

  
        if ($response == false) {
            return false;
        }

        $response_api = @json_decode($response);

        if ($response_api->error != 0) {
            return false;
        }

        return true;
    }
}
