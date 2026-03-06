<?php
require_once ("App_console.php");

class Update_cod_trab_candidates extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->from('tbl_recruitment_contracts');
        $this->db->where('no_cia', '99');
        $this->db->limit(12);
        $results = $this->db->get()->result();

        foreach ($results as $row_contract) {
            
            $document_number = $row_contract->identification_doc_number;
            $seeker_id = $row_contract->seeker_id;
            $job_id = $row_contract->job_id;

            $request = $this->get_request($job_id);
        
            if (!$request) {
                continue;
            }

            $no_cia = $request->no_cia;

            if (!$no_cia) {
                continue;
            }

            $cod_trab = $this->get_cod_trab($document_number, $no_cia);

            if (!$cod_trab) {
                continue;
            }

            $this->db->where('id', $row_contract->id);
            $this->db->update('tbl_recruitment_contracts', [
                'no_cia' => $no_cia,
                'cod_trab' => $cod_trab
            ]);

            $this->db->where('ID', $seeker_id);
            $this->db->update('tbl_job_seekers', [
                'employee_code' => $cod_trab
            ]);
        }
    }

    private function get_request($job_id)
    {
        $this->db->select([
            'r.no_cia AS no_cia'
        ]);
        $this->db->from('tbl_post_jobs j');
        $this->db->join('tbl_staff_requests r', 'r.ID=j.request_ID');
        $this->db->where('j.ID', $job_id);
        return $this->db->get()->row();
    }

    private function get_cod_trab($document_number, $no_cia)
    {
        $cod_trab = false;

        $url = $this->config->item('hrm_api2_url') . "/trabajador/obtener_cod_trab?" . 'doc_identidad_numero=' . $document_number . '&no_cia=' . $no_cia;
        
        $headers = [
            "Content-Type: application/json"
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
			//CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		$response = curl_exec($ch);
        
        curl_close($ch);
        if ($response === false) {
            return false;
        }
        
        $json_response = json_decode($response);

        if ($json_response->error == 0 && isset($json_response->data->cod_trab)) {
            $cod_trab = $json_response->data->cod_trab;
        }
        
        return $cod_trab;
    }
}
