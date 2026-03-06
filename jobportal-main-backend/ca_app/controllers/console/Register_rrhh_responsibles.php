<?php
require_once ("App_console.php");

class Register_rrhh_responsibles extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $employers_responsibles = $this->get_employers_responsibles();

        //dd($employers_responsibles);
        if ($employers_responsibles === false) {
            return;
        }

        $this->db->where('company_id', 1);
        $this->db->delete('tbl_rrhh_responsibles');

        foreach ($employers_responsibles as $row) {

            $email = strtolower(trim($row->correo));

            if ($email == '') {
                continue;
            }

            $this->db->insert('tbl_rrhh_responsibles', [
                'email' => $email,
                'document_number' => trim($row->doc_iden_numero),
                'cia_code' => trim($row->cia_codigo),
                'client_code' => trim($row->cliente_codigo),
                'business_unit_code' => trim($row->unidad_negocio_codigo),
                'company_id' => 1
            ]);
        }
    }

    private function get_employers_responsibles()
    {
        $url = $this->config->item('hrm_api2_url') . "/rrhh_responsables/listado";
        
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

        if ($json_response->error == 0) {
            return $json_response->data;
        }

        return false;
    }
}
