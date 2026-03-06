<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Commands extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
    }

    public function migrar()
    {
    	$this->db->select([
    		'seekers.*',
    		'doc_type.id as doc_number_type'
    	]);
    	$this->db->from('tbl_seeker_identification_documents seeker_docs');
    	$this->db->join('tbl_job_seekers seekers', 'seeker_docs.seeker_ID=seekers.ID');
		$this->db->join('tbl_identity_document_types doc_type', 'seekers.document_type=doc_type.key', 'left');

    	$seekers = $this->db->get()->result();

    	foreach ($seekers as $seeker) {
    		
    		$doc_type_id = $seeker->doc_number_type == null ? 1 : $seeker->doc_number_type;

    		$this->db->where('seeker_ID', $seeker->ID);
    		$this->db->update('tbl_seeker_identification_documents', [
    			'doc_type' => $doc_type_id
    		]);
    	}
    }
}
