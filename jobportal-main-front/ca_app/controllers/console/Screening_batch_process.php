<?php
require_once ("App_console.php");

class Screening_batch_process extends App_console  
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
            'batch_items.id AS batch_item_id',
            'batch_items.document_number AS document_number',
            'batch_items.type_id AS type_id',
            'batch_items.job_title AS job_title',
            'batch_items.no_cia AS no_cia',
            'batch_items.type_expense AS type_expense',
            'batch_items.cost_center AS cost_center',
            'batch_items.cost_center_client AS cost_center_client',
            'batch_items.eecc_code AS eecc_code'
            
        ]);
        $this->db->from('tbl_screening_batch batch');
        $this->db->join('tbl_screening_batch_items batch_items', 'batch_items.batch_id=batch.id');
        $this->db->where('batch.status_id', 1);
        $this->db->where('batch_items.screening_id', null);
        $this->db->order_by('batch_items.id', 'ASC');
        $this->db->limit(10);

        $results = $this->db->get()->result();

        //dd($results);

        foreach ($results as $screening_item) {
            $this->process($screening_item);
            sleep(3);
        }    
    }

    private function process($screening_item)
    {       
        //dd($screening_item);
        $this->load->library('Screening/Screening_jobseeker_search_lib');
        
        $document_number = $screening_item->document_number;
        $type = $screening_item->type_id;
        $cost_center = $screening_item->cost_center;

        $this->db->from('tbl_job_seekers');
        $this->db->where('document_number', $document_number);
        $jobseeker = $this->db->get()->row();
        
        $response = $this->screening_jobseeker_search_lib->create([
            'document_number' => $document_number,
            'type' => $type,
            'seeker_id' => $jobseeker ? $jobseeker->ID : null,
            'cost_center' => $cost_center,
            'job_title' => $screening_item->job_title,
            'type_expense' => $screening_item->type_expense,
            'eecc_code' => $screening_item->eecc_code,
            'cost_center_client' => $screening_item->cost_center_client,
            'created_by_id' => $screening_item->created_by_id
        ]);
        
        if (isset($response['data']['id'])) {
            $this->db->where('id', $screening_item->batch_item_id);
            $this->db->update('tbl_screening_batch_items', [
                'screening_id' => $response['data']['id']
            ]);

            if ($this->batch_is_complete($screening_item->batch_id)) { 
                $this->db->where('id', $screening_item->batch_id);
                $this->db->update('tbl_screening_batch', [
                    'status_id' => 2
                ]);
            }
        }

        if ($response['status'] == true) {
            echo $document_number . ' -> OK' . "\n";
        }

        if ($response['status'] == false) {
            echo $document_number . ' -> ' . $response['message'] . "\n";
        }
    }

    private function batch_is_complete($batch_id)
    {
        $this->db->from('tbl_screening_batch_items');
        $this->db->where('batch_id', $batch_id);
        $this->db->where('screening_id', null);
        $count = $this->db->count_all_results();

        if ($count == 0) {
            return true;
        }

        return false;
    }
}
