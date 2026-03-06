<?php
class Requested_document extends CI_Model
{  
    public function save_identification_document( 
        $seeker_id, 
        $doc_type, 
        $path
    )
    {
    	$this->db->where('seeker_ID', $seeker_id)
            ->where('doc_type', $doc_type)
            ->delete('tbl_seeker_identification_documents');

    	$data = [
    		'seeker_ID' => $seeker_id,
            'doc_type' => $doc_type,
    		'path' => $path
    	];

    	return $this->db->insert('tbl_seeker_identification_documents', $data);
    }
 
    public function get_identity_documents($seeker_id, $required = false)
    {
        $join_type = $required ? 'inner' : 'left';

        $this->db->select([
            'seeker_doc_file.path',
            'di_type.name',
            'di_type.id AS doc_id'
        ])
        ->from('tbl_identity_document_types di_type')
        ->join(
            'tbl_seeker_identification_documents seeker_doc_file', 
            'seeker_doc_file.seeker_ID="' . $seeker_id . '" AND  di_type.id=seeker_doc_file.doc_type',
            $join_type 
        )
        ->where_in('di_type.id', [1, 4, 7, 12, 13]);

        return $this->db->get()->result();
    }

    public function get_identity_documents_by_type($seeker_id, $doc_type = [])
    {
        $this->db->select([
            'seeker_doc_file.path',
            'di_type.name',
            'di_type.id AS doc_id'
        ])
        ->from('tbl_identity_document_types di_type')
        ->join(
            'tbl_seeker_identification_documents seeker_doc_file', 
            'seeker_doc_file.seeker_ID="' . $seeker_id . '" AND  di_type.id=seeker_doc_file.doc_type',
            'left' 
        )
        ->where_in('di_type.id', $doc_type);

        return $this->db->get()->result();
    }
    
    public function get_identification_document($seeker_id, $doc_type = null)
    {
    	$this->db->from('tbl_seeker_identification_documents');
    	$this->db->where('seeker_ID', $seeker_id);

        if ($doc_type !== null) {
            $this->db->where('doc_type', $doc_type);
        }

    	return $this->db->get()->row();
    }

    public function delete_identification_document($seeker_id, $doc_type) {
        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('doc_type', $doc_type);
        return $this->db->delete('tbl_seeker_identification_documents');
    }

    public function delete_file_from_storage($path) {
        if ($this->storage_lib->has($path)) {
            return $this->storage_lib->delete($path);
        }
        return false;
    }

    public function save_spouse($data)
    {
        $this->db->where('seeker_ID', $data['seeker_ID']);
        $this->db->delete('tbl_seeker_spouse');

        return $this->db->insert('tbl_seeker_spouse', $data);
    }

    public function get_spouse($seeker_id)
    {
        $this->db->from('tbl_seeker_spouse');
        $this->db->where('seeker_ID', $seeker_id);

        return $this->db->get()->row();
    }

    public function save_receipt_service($seeker_id, $path)
    {
        $this->db->where('seeker_ID', $seeker_id);
        $this->db->delete('tbl_seeker_receipt_service');

        $data = array(
            'seeker_ID' => $seeker_id,
            'file_name' => $path
        );

        return $this->db->insert('tbl_seeker_receipt_service', $data);
    }

    public function get_receipt_service($seeker_id)
    {
        $this->db->from('tbl_seeker_receipt_service');
        $this->db->where('seeker_ID', $seeker_id);

        return $this->db->get()->row();
    }

    public function save_photo($seeker_id, $file_name)
    {
        $this->db->where('seeker_ID', $seeker_id);
        $this->db->delete('tbl_seeker_document_photos');

        $data = array(
            'seeker_ID' => $seeker_id,
            'file_name' => $file_name
        );

        return $this->db->insert('tbl_seeker_document_photos', $data);
    }

    public function get_photo($seeker_id)
    {
        $this->db->from('tbl_seeker_document_photos');
        $this->db->where('seeker_ID', $seeker_id);

        return $this->db->get()->row();
    }
}
