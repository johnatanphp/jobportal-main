<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_importer extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function import()
	{
        $this->load->library(
            'Imports/Entry_seekers_other_site_import',
            null,
            'Entry_seekers_other_site_import'
        );

        $all_inputs = $this->input->post();
        $all_inputs['add_rys_process'] = 1;

        $import_data = $this->Entry_seekers_other_site_import->import(
            $all_inputs
        );

        if ($import_data['status'] == true) {
            echo json_encode([
                'status' => true,
                'message' => $import_data['message']
            ]);
            return;
        }

        echo json_encode([
            'status' => false,
            'message' => $import_data['message']
        ]);
    }
}
