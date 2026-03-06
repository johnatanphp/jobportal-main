<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_api_v2_Controller.php';

class Api_v2_Controller extends REST_api_v2_Controller
{
    public function __construct() 
	{
        parent::__construct("rest_v2", ['rest_auth' => 'bearer_token']);

        $this->load->library('Session/Session_api_v2_employer', [], 'session_employer_lib');
        $this->session_employer_lib->set_token($this->get_bearer_token());
        $this->session_employer_lib->build_data();
    }

    public function __destruct()
    {
        $token = $this->session_employer_lib->get_data('token');
        $date = new DateTime('now');

        $date->add(new DateInterval('PT1H'));
        $expires_at = $date->format('Y-m-d H:i:s');

        $this->db->where('access_token', $token);
        $this->db->where('revoked', 0);
        $this->db->update('tbl_api_user_tokens', [
            'expires_at' => $expires_at,
        ]);
    }
}
