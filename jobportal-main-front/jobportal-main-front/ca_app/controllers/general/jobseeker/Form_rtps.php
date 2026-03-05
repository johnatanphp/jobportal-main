<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Form_rtps extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
    }

    public function view($id = null)
    {
        $form_rtps_id = $this->custom_encryption->decrypt_data($id, 1);
   
		if (!$form_rtps_id) {
			show_404();
		}

        $this->load->library('Pdf/Form_rtps_pdf');

        $this->db->from('tbl_seeker_form_rtps');
        $this->db->where('ID', $form_rtps_id);
        $form_rtps = $this->db->get()->row();

        if (!$form_rtps) {
            show_404();
        }

        $this->form_rtps_pdf->show($form_rtps);
    }
}
