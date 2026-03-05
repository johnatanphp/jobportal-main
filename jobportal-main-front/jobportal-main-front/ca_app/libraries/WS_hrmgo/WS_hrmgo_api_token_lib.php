<?php
class WS_hrmgo_api_token_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_token($company_id, $params = []) 
    {
        $this->load->model('Hrmgo_api_conection');

         if (is_array($params) && count($params) > 0) {
            $api_url = $params['api_url'];
            $api_username = $params['api_username'];
            $api_password = $params['api_password'];
        } else {

            $hrmgo_api = $this->Hrmgo_api_conection->find(['company_id' => $company_id]);

            if (!$hrmgo_api) {
                return null;
            }

            if ($hrmgo_api->active != 1) {
                return null;
            }

            $api_url = $hrmgo_api->api_url;
            $api_username = $hrmgo_api->api_username;
            $api_password = $hrmgo_api->api_password;
        }

        $credentials = [
            'email' => $api_username,
            'password' => $api_password
        ];

        $login_url = $api_url . "/auth/login";

        $login_options = [
            CURLOPT_URL => $login_url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_POSTFIELDS => json_encode($credentials),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $login_options);
        $login_response = curl_exec($ch);
        curl_close($ch);

        $login_data = @json_decode($login_response);

        if (!$login_data || empty($login_data->token)) {
            log_message('error', 'Login API HRMGO failed: ' . $login_response);
            return null;
        }

        $hrmgo_api->token = $login_data->token;

        return $hrmgo_api;
    }
}
