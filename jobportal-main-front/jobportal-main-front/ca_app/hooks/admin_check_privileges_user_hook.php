<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_check_privileges_user_hook
{
	public function index() 
    {
		$CI = & get_instance();
		$folder = $CI->uri->segment(1);
		$controller = $CI->uri->segment(2);
		$user_id = $CI->session->userdata('admin_id');		
		
		if ($folder != 'admin' || 
			empty($user_id) || 
			$user_id == 1) {
			return;
		}
		
		if ($user_id == 2) {
			if ($controller == 'dashboard') {
				redirect('admin/job_layouts');
			}

			if ($controller == '' || 
				$controller == 'mofs' || 
				$controller == 'home' || 
				$controller == 'job_profiles' ||
				$controller == 'job_layouts' ||
				$controller == 'job_layout_permissions') {
				return;
			}
		}

		if ($user_id == 3) {
			if ($controller == 'dashboard') {
				redirect('admin/job_seekers');
			}

			if ($controller == '' || 
				$controller == 'job_seekers' || 
				$controller == 'home') {
				return;
			}
		}

		show_404();
    }
}
