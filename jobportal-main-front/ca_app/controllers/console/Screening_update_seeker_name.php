<?php
require_once ("App_console.php");

class Screening_update_seeker_name extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'ts.id AS id',
            'ts.response AS response'
        ]);
        $this->db->from('tbl_screening ts');
        $this->db->where('ts.first_name', '');
        $this->db->where('ts.response_code', 1);
        $this->db->order_by('ts.id', 'DESC');
        $this->db->limit($this->config->item('screening_update_name_limit') ? $this->config->item('screening_update_name_limit') : 0);
        $results = $this->db->get()->result();

        foreach ($results as $row) {
            $response = json_decode($row->response);

            $seeker_data = $response->data;

            $this->db->where('id', $row->id);
            $this->db->update('tbl_screening', [
                'first_name' => trim($seeker_data->firstname),
                'last_name' => trim(trim($seeker_data->lastname) . ' ' . trim($seeker_data->secondSurname))
            ]);
        }
    }
}