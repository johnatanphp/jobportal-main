<?php
class Whatsapp_candidate_registration_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($candidate_id)
    {
        $candidate = $this->Job_seeker->find($candidate_id);

        if (!$candidate) {
            return false;
        }   

        $mobile = format_mobile($candidate->mobile);

        if (!$mobile) {
            return false;
        }

        $url = $this->config->item('hrm_api2_url') . '/whatsapp/candidate/register_portal/' . $mobile;

        $params = [
            'candidate_name' => trim($candidate->first_name),
            'link_login' => site_url('login')
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_TIMEOUT => 20 //Segundos
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        //dd($response);

        if ($response == false) {
            return false;
        }

        $response_api = @json_decode($response);

        if (isset($response_api->error) && $response_api->error == 0) {
            return true;
        }

        return false;
    }
}
