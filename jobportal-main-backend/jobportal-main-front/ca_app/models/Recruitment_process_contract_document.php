<?php
class Recruitment_process_contract_document extends CI_Model
{    
    public function get_documents($job_id)
    {
        $job = $this->Posted_job->find($job_id);
        $seeker_id = $this->session->userdata('user_id');

        // Si el empleo proviene de la bandeja de reclutamiento tray
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.id=tray_candidates.process_id');
        $this->db->where('recruitment_process.job_ID', $job->ID);
        $tray_candiate = $this->db->get()->row();

        if ($tray_candiate) {

            $filter_doc_ids = [
                1,
                2,
                3,
                14,
                15
            ];

            $this->db->select([
                'dt.id',
                'dt.name',
                'dt.id AS document_id',
                'dt.option_type_id',
                'dt.group_id'
            ]);
            $this->db->from('tbl_recruitment_contract_document_types dt');
            $this->db->where('dt.company_id', 1);
            $this->db->where_in('dt.id', $filter_doc_ids);
            $this->db->where('dt.active', 1);

            return $this->db->get()->result();
        }

        $staff_request = $this->Staff_request->find($job->request_ID);

        // Si el empleo proviene de la solicitud de la  emplesa overall
        // y el modelo de la solicitud es modelo 3 se listara documentos personalizados

        if ($staff_request && $staff_request->company_ID == 1 && $staff_request->request_model_id == 3) {

            $filter_doc_ids = [
                1,
                2,
                3,
                14,
                15
            ];

            $this->db->select([
                'dt.id',
                'dt.name',
                'dt.id AS document_id',
                'dt.option_type_id',
                'dt.group_id'
            ]);
            $this->db->from('tbl_recruitment_contract_document_types dt');
            $this->db->where('dt.company_id', $staff_request->company_ID);
            $this->db->where_in('dt.id', $filter_doc_ids);
            $this->db->where('dt.active', 1);

            return $this->db->get()->result();
        }

        $this->db->from('tbl_recruitment_process_contract_documents');
        $this->db->where('job_id', $job_id);
        $count = $this->db->count_all_results();

        if ($count == 0) {
            $this->db->select([
                'dt.id',
                'dt.name',
                'dt.id AS document_id',
                'dt.option_type_id',
                'dt.group_id'
            ]);
            $this->db->from('tbl_recruitment_contract_document_types dt');
            $this->db->where('dt.company_id', $job->company_ID);
            $this->db->where('dt.active', 1);
            $documents = $this->db->get()->result();
        }

        if ($count > 0) {
            $this->db->select([
                'dt.id',
                'dt.name',
                'cd.document_id',
                'dt.option_type_id',
                'dt.group_id'
            ]);
            $this->db->from('tbl_recruitment_contract_document_types dt');
            $this->db->join('tbl_recruitment_process_contract_documents cd', 'dt.id=cd.document_id AND cd.job_id="' . $job_id . '"', 'left');
            $this->db->where('dt.active', 1);
            $this->db->where('dt.company_id', $job->company_ID);
            
            $documents = $this->db->get()->result();
        }

        return $documents;
    }
}
