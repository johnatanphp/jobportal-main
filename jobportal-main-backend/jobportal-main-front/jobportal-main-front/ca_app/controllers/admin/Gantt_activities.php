<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gantt_activities extends CI_Controller {
	
	public function d($key = 0)
	{

		if ($key == 0) {
			$key = '20191';
		}

		$year = substr($key, 0, 4);
		$month = substr($key, 4, strlen($key));

		$data['month'] = $month;
		$data['year'] = $year;

		$this->load->view('admin/gantt_register_non_working_days_view', $data);
	}
}