<?php
class Recruitment_process_document extends CI_Model
{
    public function get_documents($job_id)
    {
        $user = $this->Employer->find($this->session->userdata('user_id'));
        $company = $this->Company->find($user->company_ID);

        $this->db->from('tbl_recruitment_process_documents');
        $this->db->where('job_id', $job_id);
        $count = $this->db->count_all_results();

        if ($count == 0) {

            $this->db->select([
                'dt.id',
                'dt.name',
                'dt.key',
                'dt.id AS document_id',
                'dt.option_type_id'
            ]);
            $this->db->from('tbl_recruitment_document_types dt');
            $this->db->where('dt.country_id', $company->country_id);
            $this->db->where('dt.active', 1);
            $documents = $this->db->get()->result();
        }

        if ($count > 0) {
            $this->db->select([
                'dt.id',
                'dt.name',
                'dt.key',
                'pd.document_id',
                'dt.option_type_id'
            ]);
            $this->db->from('tbl_recruitment_document_types dt');
            $this->db->join('tbl_recruitment_process_documents pd', 'dt.id=pd.document_id AND pd.job_id="' . $job_id . '"', 'left');
            $this->db->where('dt.active', 1);
            $this->db->where('dt.country_id', $company->country_id);
            
            $documents = $this->db->get()->result();
        }

        return $documents;
    }
}
