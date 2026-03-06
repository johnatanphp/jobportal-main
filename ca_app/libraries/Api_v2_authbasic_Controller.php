<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_api_v2_Controller.php';

class Api_v2_authbasic_Controller extends REST_api_v2_Controller
{
    public function __construct() 
	{
        parent::__construct("rest_v2", ['rest_auth' => 'basic']);
    }
}
