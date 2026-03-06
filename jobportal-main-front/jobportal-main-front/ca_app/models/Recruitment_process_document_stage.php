<?php
class Recruitment_process_document_stage extends CI_Model
{
    public function get_documents($job_id, $stage_id)
    {
        $user = $this->Employer->find($this->session->userdata('user_id'));
        $company = $this->Company->find($user->company_ID);

        $this->db->select([
            'dt.id',
            'dt.name',
            'dt.key',
            'dt.option_type_id',
            'rpds.document_id'
        ]);
        $this->db->from('tbl_recruitment_document_types dt');
        $this->db->join('tbl_recruitment_process_documents_stages rpds', 'rpds.stage_id="' . $stage_id . '" AND dt.id=rpds.document_id AND rpds.job_id="' . $job_id . '"', 'left');
        
        $this->db->where('dt.active', 1);
        $this->db->where('dt.country_id', $company->country_id);
        
        return $this->db->get()->result();
    }
}
