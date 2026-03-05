<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class File extends CI_Controller
{
	public function download()
	{	
		$url = $this->input->get('url');
	
		if (empty($url)) {
			show_404();
		}

		if (substr($url, 0, 49) != 'https://overall-portal-de-empleo.s3.amazonaws.com') {
			show_404();
		}

		$data = file_get_contents($url);
		$part = explode('/', $url);
		$file_name = end($part);

		force_download($file_name, $data);
		exit;
	}
}
