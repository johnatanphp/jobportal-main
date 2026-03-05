<?php
class Recruitment_candidate_fits_tmp extends CI_Model 
{
    public function create_table($filters)
    {
        $query1 = "CREATE TEMPORARY TABLE tbl_recruitment_candidate_fits_tmp ("
                            . "seeker_id int(11),"
                            . "is_fit int(11)"
                            . ");";
 
        $result_table_tmp = $this->db->query($query1);

        if (!$result_table_tmp) {
            return [];
        }

        $this->db->select([
            'js.ID AS seeker_id',
            'js.document_number'
        ]);
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_seeker_entries se', 'se.seeker_id=js.ID');

        if (isset($filters['channel']) && $filters['channel'] != '') {
            $this->db->where('se.recruitment_channel', $filters['channel']);
        }

        if (isset($filters['job_title']) && $filters['job_title'] != '') {
            $this->db->where('se.job_title', $filters['job_title']);
        }

        if (isset($filters['client']) && $filters['client'] != '') {
            $this->db->where('se.company_account', $filters['client']);
        }

        $results = $this->db->get()->result();

        $seekers_ids = [];

        foreach ($results as $row) {
            $seekers_ids[] = $row->seeker_id;
        }

        if (count($seekers_ids) == 0) {
            return [];
        }
        
        //Buscar candidatos en otros procesos
        $rs_candidates = $this->get_list_candidates_in_process($seekers_ids); 
        
        //Verificar candidatos en lista negra
        $blacklist = $this->get_blacklist($results);

        $insert = [];
   
        foreach ($results as $seeker) {

            $seeker_id = $seeker->seeker_id;
            $document_number = $seeker->document_number;

            $is_fit = 1;

            //Verificar si el candidatos esta en un proceso
            if ($is_fit) {
                $is_fit = isset($rs_candidates[$seeker_id]) ? 0 : 1;
            }

            //Verificar candidatos esta en lista negra
            if ($is_fit) {
                $is_fit = !(isset($blacklist[$document_number]) && ($blacklist[$document_number])->blacklist == 1);
            }

            $insert[] = [
                'seeker_id' => $seeker_id,
                'is_fit' => $is_fit
            ];
        }

        if (count($insert) > 0) {
            $this->db->insert_batch('tbl_recruitment_candidate_fits_tmp', $insert); 
        }
    }

    private function get_list_candidates_in_process($seekers_ids)
    {
        $rs_candidates = []; 

        //Buscar candidatos en otros procesos
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->where_in('seeker_id', $seekers_ids);
        $this->db->where('discarded', 0);
        $this->db->where('contracted', 0);
        
        foreach ($this->db->get()->result() as $row) {
            $rs_candidates[$row->seeker_ID] = $row; 
        }

        return $rs_candidates;
    }

    private function get_blacklist($results)
    {
        $blacklist = [];

        $document_numbers = [];
        foreach ($results as $row) {
            $document_numbers[] = $row->document_number;
        }

        $blacklist = $this->Job_seeker->get_overall_blacklist($document_numbers);

        return $blacklist;
    }
}
