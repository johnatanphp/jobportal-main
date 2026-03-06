<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Oauth2 extends CI_Controller
{	
	public function __construct()
    {
        parent::__construct();
    }
    
    public function authorize()
    {      
        if ($this->session->userdata('is_job_seeker')) {
            redirect('jobseeker/dashboard');
        }
            
        $user_id = $this->session->userdata('user_id');
         
        $params = $this->input->get();       
        $redirect_uri = isset($params['redirect_uri']) ? urldecode($params['redirect_uri']) : null;
        
        if (!$redirect_uri) {
            echo 'Error: redirect_uri es requerido';
            return;
        }
        
        $uri_is_valid = filter_var(
            $redirect_uri, 
            FILTER_VALIDATE_URL, 
            FILTER_FLAG_PATH_REQUIRED
        );
        
        if (!$uri_is_valid) {
            echo 'Error: redirect_uri tiene un formato incorrecto';
            return;
        }
        
        if ($this->config->item('env') == 'production' && $redirect_uri != 'https://overall.limapixel.com/login') {
            echo 'Error: redirect_uri no es aceptada';
            return;
        }
        
        $response_type = $params['response_type'] ?? null;
        
        if ($response_type != 'code') {
            header('Location: ' . $redirect_uri . '?error=unsupported_response_type');
            return;
        }
        
        if (!$user_id) {
            $this->session->set_userdata('back_from_user_login', ($this->uri->uri_string() . '?' .  $_SERVER['QUERY_STRING'] ?? ''));
            redirect('employer-login');
        }
        
        $state = $params['state'] ?? null;   
        $code_challenge = $params['code_challenge'] ?? null; 
        $code = md5(uniqid(true, time()));
         
        $date = new DateTime('now');
        $date->add(new DateInterval('PT5M')); // Sumar 5 minutos
        $expires_at = $date->format('Y-m-d H:i:s');
    
        $autorization_code = $this->db->insert('tbl_api_user_auth_codes', [
            'code' => $code,
            'user_type' => 'employer',
            'user_id' => $user_id,
            'redirect_uri' => $redirect_uri,
            'code_challenge' => $code_challenge,
            'expires_at' => $expires_at
        ]);
        
        if (!$autorization_code) {
            header('Location: ' . $redirect_uri . '?error=server_error');
            return;
        }
            
        $auth_codes = $this->session->userdata('employer_auth_codes') ? $this->session->userdata('employer_auth_codes') : [];
        
        $auth_codes[] = $code;
        $this->session->set_userdata('employer_auth_codes', $auth_codes);
        
        $redirect_uri = $redirect_uri . '?code=' . $code  . ($state !== null ? '&state=' . $state : '');
        
        header('Location: ' . $redirect_uri);
    }
}
