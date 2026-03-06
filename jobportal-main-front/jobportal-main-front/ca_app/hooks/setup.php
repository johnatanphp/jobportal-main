<?php

$ci =& get_instance();

if ($ci->config->item('maintenance_mode') == 1) {
	site_maintenance();
}

function site_maintenance($ip_allowed = '')
{
	$ci =& get_instance();

	$allowed_ips = explode(',', $ci->config->item('maintenance_mode_allowed_ips'));

	if (!in_array($ci->input->ip_address(), $allowed_ips) && !is_cli()) {
		echo $ci->load->view('errors/html/maintenance_mode', null, true);
		exit(4);
	}
}

function jobseeker_accepted_legal_terms()
{
	$ci =& get_instance();
	
	$seeker_id = $ci->session->userdata('user_id');

	return $ci->db->get_where('tbl_seeker_acceptance_legal_terms', 
		[
			'seeker_ID' => $seeker_id
		]
	)->row() != null;
}