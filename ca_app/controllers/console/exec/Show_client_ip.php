<?php
class Show_client_ip extends CI_Controller
{
	public function index()
	{
		echo '2.0 - IP 116: ' . $this->input->ip_address();
	}
}
