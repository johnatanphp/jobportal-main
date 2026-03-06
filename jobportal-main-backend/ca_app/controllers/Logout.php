<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('App/Session/Session_logout');
        $this->session_logout->exec();
        
        redirect(base_url(), 'refresh');
    }
}
