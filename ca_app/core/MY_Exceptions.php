<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions
{
    public function api_show_404($message = 'Unknown method')
	{   
        header("Content-Type: application/json");
        http_response_code(404); 
        echo json_encode([
            'status' => false,
            'message' => $message
        ]);
	}
}
