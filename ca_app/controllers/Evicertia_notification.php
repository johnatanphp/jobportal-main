<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Evicertia_notification extends CI_Controller 
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

    public function seeker_requested_documents($doc_key = '') 
    {
    	$data = [];
        $evicertia_data = json_decode($this->input->raw_input_stream, true);
		
		$evidence_id = $evicertia_data['EvidenceId'];
    	$kind = $evicertia_data['Kind'];

		if (in_array($kind, ["FullySigned", "Signed"])) {
			$data = ['evicertia_status' => 3];
		} else if ($kind == "Rejected") {
			$data = ['evicertia_status' => 4];
		} else if ($kind == "Closed") {
			
			$additional_data = $evicertia_data['AdditionalData'];

			if ($additional_data['OutCome'] == "Failed") {
				$data = ['evicertia_status' => 5];
			} else if ($additional_data['OutCome'] == "Expired") {
				$data = ['evicertia_status' => 6];
			}
		}
		
    	if ($doc_key == 'domicile_affidavit') {
    		$this->db->where('evicertia_unique_id', $evidence_id);
    		$this->db->update('tbl_seeker_domicile_affidavits', $data);
    	}

    	if ($doc_key == 'declaration_5th_category') {
    		$this->db->where('evicertia_unique_id', $evidence_id);
    		$this->db->update('tbl_seeker_declaration_5th_categories', $data);
    	}	

		if ($doc_key == 'form_rtps') {
    		$this->db->where('evicertia_unique_id', $evidence_id);
    		$this->db->update('tbl_seeker_form_rtps', $data);
    	}	
    }
}
