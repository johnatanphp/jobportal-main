<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends CI_Controller 
{	
	public function __construct()
    {
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
    
    public function index()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Reportes operativos';
        $this->load->view('employer/operational_reports/reports', $data);
    }
}
