<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Session_api_v2_employer
{   
    private $token = null; 

    private $data = null; 

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function set_token($token = null)
    {
        $this->token = $token;
    }

    public function build_data()
    {
        if ($this->data) {
            return;
        }

        if ($this->config->item('load_employer_by_default')) {
            $this->data = [
                'user_id' => $this->config->item('load_employer_by_default'),
                'token' => null,
                'app_user_id' => null
            ];
            return;
        }
        
        $token = $this->token;
        $token_row = $this->db->get_where('tbl_api_user_tokens', [
            'access_token' => $token,
            'user_type' => 'employer'
        ])->row();

        if (!$token_row) {
            return;
        }

        $this->data = [
            'user_id' => $token_row->user_id,
            'token' => $token_row->access_token,
            'app_user_id' => $token_row->app_user_id
        ];
    }

    public function data()
    {
        return $this->data;
    }

    public function get_data($key)
    {
        $data = $this->data();

        return $data[$key] ?? null;
    }
}
