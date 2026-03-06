<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        
        $this->load->library('Url_signer/Url_signer_lib');
    }

    public function view($data_encrypt = null, $display_type = 1)
    {
        if ($display_type < 1 || $display_type > 3) {
            show_404();
        }
        
        $current_url = site_url($this->uri->uri_string());
        
        if ($_SERVER['QUERY_STRING']) {
            $current_url .= '?' . $_SERVER['QUERY_STRING'];
        }
        
		$url_is_valid = $this->url_signer_lib->validate($current_url);
		
		if (!$url_is_valid) {
		    show_404();
		}
					
	    $screening_value_id = $this->custom_encryption->decrypt_data($data_encrypt, 1);
					
		if (!ctype_digit($screening_value_id)) {
			show_404();
		}

        $this->load->library('Pdf/Screening_jobseeker_pdf');

        $this->screening_jobseeker_pdf->show($screening_value_id, $display_type - 1);
    }
}
