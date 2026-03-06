<?php

if ($this->session->userdata('is_job_seeker')) {
	$this->load->view('jobseeker/common/jobseeker_menu');
} else {
	$this->load->view('employer/common/menu/sidebar');
}
