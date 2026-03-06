<?php
require_once ("App_console.php");

class Recruitment_tray_screening_batches extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'batch.id AS batch_id',
            'batch.created_by AS created_by_id',
            'batch.document_number AS document_number',
            'batch.type_id AS type_id',
            'batch.seeker_id AS seeker_id',
            'batch.job_title AS job_title',
            'batch.type_expense AS type_expense',
            'batch.cost_center AS cost_center',
            'batch.cost_center_client AS cost_center_client',
            'batch.eecc_code AS eecc_code'
        ]);
        $this->db->from('tbl_recruitment_tray_screening_batches batch');
        $this->db->where('batch.status_id', 1); //Generando
        $this->db->order_by('batch.id', 'ASC');
        $this->db->limit(5);

        $results = $this->db->get()->result();

        //dd($results);

        foreach ($results as $screening_item) {
            $this->create_screening($screening_item);
            sleep(3);
        }    
    }

    private function create_screening($screening_item)
    {       
        $this->load->library('Screening/Screening_jobseeker_search_lib');
        
        $document_number = $screening_item->document_number;
        $seeker_id = $screening_item->seeker_id;
        $type = $screening_item->type_id;
        $cost_center = $screening_item->cost_center;
 
        $response = $this->screening_jobseeker_search_lib->create([
            'document_number' => $document_number,
            'type' => $type,
            'seeker_id' => $seeker_id,
            'cost_center' => $cost_center,
            'job_title' => $screening_item->job_title,
            'type_expense' => $screening_item->type_expense,
            'eecc_code' => $screening_item->eecc_code,
            'cost_center_client' => $screening_item->cost_center_client,
            'created_by_id' => $screening_item->created_by_id
        ]);
        
        if ($response['status'] == true) {
            $this->db->where('id', $screening_item->batch_id);
            $this->db->update('tbl_recruitment_tray_screening_batches', [
                'screening_id' => $response['data']['id'],
                'status_id' => 2 //Generado
            ]);
            echo $document_number . ' -> OK' . "\n";
        }

        if ($response['status'] == false) { 
            $this->db->where('id', $screening_item->batch_id);
            $this->db->update('tbl_recruitment_tray_screening_batches', [
                'screening_id' => isset($response['data']['id']) ? $response['data']['id'] : null,
                'status_id' => 3 //Fallido
            ]);
            echo $document_number . ' -> ' . $response['message'] . "\n";
        }
    }

    // private function batch_is_complete($batch_id)
    // {
    //     $this->db->from('tbl_screening_batch_items');
    //     $this->db->where('batch_id', $batch_id);
    //     $this->db->where('screening_id', null);
    //     $count = $this->db->count_all_results();

    //     if ($count == 0) {
    //         return true;
    //     }

    //     return false;
    // }
}
