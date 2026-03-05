<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public function index()
	{			
		$data['title'] = 'Dashboard - ' . SITE_NAME;
		$data['msg'] = '';
		
		$this->load->view('admin/dashboard_view', $data);
	}
}
