<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Legal_terms extends CI_Controller {
	
	public function accept()
	{

		$seeker_id = $this->session->userdata('user_id');

		$trans_status = $this->Job_seeker->accept_legal_terms($seeker_id);

		echo json_encode(
			array(

				'success' => $trans_status
			)
		);
	}

}