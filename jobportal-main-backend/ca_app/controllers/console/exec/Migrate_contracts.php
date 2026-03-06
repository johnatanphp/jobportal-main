<?php
class Migrate_contracts extends CI_Controller
{
	public function migrate()
	{
        $this->db->select([
            'c.*',
            's.employee_code AS cod_trab',
            's.document_number',
            'doc.id AS doc_type_id'
        ]);
		$this->db->from('tbl_recruitment_candidates c');
        $this->db->join('tbl_job_seekers s', 's.ID=c.seeker_ID');
        $this->db->join('tbl_identity_document_types doc', 's.document_type=doc.key', 'left');
        
		$this->db->where('contracted', 1);
		$r = $this->db->get()->result();

		foreach ($r as $row) {

            $row_seeker = $this->db->get_where('tbl_recruitment_contracts', [
                'seeker_id' => $row->seeker_ID,
                'job_id' => $row->job_ID
            ])->row();

            if ($row_seeker) {
                continue;
            }

            $data = [
                'seeker_id' => $row->seeker_ID,
                'job_id' => $row->job_ID,
                'identification_doc_type' => $row->doc_type_id,
                'identification_doc_number' => $row->document_number,
                'is_peruvian' => $row->doc_type_id == 1 ? 1 : 0,
                'cod_trab' => $row->cod_trab,
                'no_cia' => $row->no_cia,
                'hired_at' => $row->hiring_date 
            ];
            $this->db->insert('tbl_recruitment_contracts', $data);
		}
	}
}
