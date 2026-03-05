<?php

class WS_sap_workflow_overall_update_external_lib
{
	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec()
    {
        if (!$this->config->item('sap_api_enabled')) {
            return;
        }

        $next_page = true;
        $page_limit = 5000;
        $page_offset = 0;

        $this->db->from('tbl_sap_api_users');
        $this->db->where('active', 1);
        $sap_results = $this->db->get()->result();

        foreach ($sap_results as $sap_row) {        
            while($next_page) {  
                $result = $this->get_data($sap_row->company_id, $page_limit, $page_offset);
                //dd($result);
                $this->insert_clients($sap_row->company_id, $result->value ?? []);
                $this->insert_cost_centers($sap_row->company_id, $result->value ?? []);
                $next_page = isset($result->{"odata.nextLink"});
                $page_offset+=$page_limit;
                echo $page_offset . "\n";
            }
        }
    }
    
    private function get_data($company_id, $page_limit, $page_offset)
    {       
        $this->load->library('WS_sap/WS_sap_api_token_lib', null, 'WS_sap_api_token_lib');

        $sap_token = $this->WS_sap_api_token_lib->generate($company_id);

        if (!$sap_token) {
            return [];
        }

        $token = $sap_token->token;
        $headers = [
			"Authorization: Bearer " . $token,
            "Cookie: B1SESSION=" . $token,
            "Prefer: odata.maxpagesize=" . $page_limit
        ];

        $select_fields = [
            'U_EXX_ADCT_DSCS', //CONSULTORA
            'U_EXX_ADCT_CDCL', //CLIENTE CODIGO
            'U_EXX_ADCT_DSCL', //CLIENTE NOMBRE
            'U_EXX_ADCT_CDUN', //CODIGO UNIDAD NEGOCIO
            'U_EXX_ADCT_CDCO', //CENTRO DE COSTO
            'U_EXX_ADCT_DSUN' //NOMBRE UNIDAD NEGOCIO
        ];

        $url = $sap_token->api_url . '/v1/EXX_ADCT_EECC?$select='. join(',', $select_fields) . '&$skip=' . $page_offset;

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 80,
			CURLOPT_HTTPHEADER => $headers
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		$response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return [];
        }

        return json_decode($response);
    }

    public function insert_clients($company_id, $data)
    {
        $results = $data;
        $clients = [];

        foreach ($results as $row) { 

            $client_code = trim((string)$row->U_EXX_ADCT_CDCL);
        
            if (!$client_code) {
                continue;
            }

            $clients[$client_code] = $row;
        }

        foreach ($clients as $row) {

            $client_code = trim((string)$row->U_EXX_ADCT_CDCL);
            $client_name = trim((string)$row->U_EXX_ADCT_DSCL);

            if (!$client_code || !$client_name) {
                continue;
            }

            $client = $this->db->get_where('tbl_workflow_clients', [
                'code' => $client_code,
                'company_id' => $company_id
            ])->row();
                
            if ($client && $client->active) {
                continue;
            }

            if ($client) {
                
                $data = [
                    'code' => $client_code,
                    'name' => $client_name,
                    'active' => 1
                ];

                $this->db->where('id', $client->id);
                $this->db->update('tbl_workflow_clients', $data);
                
                continue;
            }

            $data = [
                'code' => $client_code,
                'name' => $client_name,
                'active' => 1,
                'company_id' => $company_id
            ];
            $this->db->insert('tbl_workflow_clients', $data);
        }
    }

    public function insert_cost_centers($company_id, $data)
    {
        $consultants = $this->get_consultants();
        //$cost_centers = $data;
        $result = $data;
        $cost_centers = [];

        foreach ($result as $row) {

            $cost_center_code = trim((string)$row->U_EXX_ADCT_CDCO);
        
            if (!$cost_center_code) {
                continue;
            }

            $cost_center_code_part = explode('-', $cost_center_code);

            if (count($cost_center_code_part) != 5) {
                continue;
            }
            
            if (strlen($cost_center_code) != 23) {
                continue;
            }

            $consultant = $row->U_EXX_ADCT_DSCS;
            $client_code = trim((string)$row->U_EXX_ADCT_CDCL);
            $business_unit_code = trim((string)$row->U_EXX_ADCT_CDUN);
            $cost_center_code = trim((string)$row->U_EXX_ADCT_CDCO);

            $cost_centers[$consultant . '-'. $client_code . '-' . $business_unit_code . '-' . $cost_center_code] = $row;
        }

        //dd($cost_centers);
        
        foreach ($cost_centers as $row) {
            $date = date('Y-m-d H:i:s');

            $consultant_name = trim((string)$row->U_EXX_ADCT_DSCS);

            if (!$consultant_name) {
                continue;
            }

            $consultant_row = $consultants[$company_id][$consultant_name] ?? null;

            if (!$consultant_row) {
                continue;
            }

            $consultant_code = trim((string)$consultant_row->code);
            $client_code = trim((string)$row->U_EXX_ADCT_CDCL);
            $business_unit_code = trim((string)$row->U_EXX_ADCT_CDUN);
            $cost_center_code = trim((string)$row->U_EXX_ADCT_CDCO);

            if (!$consultant_code || 
                !$client_code || 
                !$business_unit_code || 
                !$cost_center_code) {
                continue;
            }

            $cc = $this->db->get_where('tbl_workflow_cost_centers', [
                'cia_code' => $consultant_code,
                'business_unit_code' => $business_unit_code,
                'client_code' => $client_code,
                'code' => $cost_center_code,
                'company_id' => $company_id
            ])->row();
                
            if ($cc) {

                $data = [
                    'active' => 1,
                    'updated_at' => $date
                ];

                $this->db->where('id', $cc->id);
                $this->db->update('tbl_workflow_cost_centers', $data);
                
                continue;
            }

            $data = [
                'cia_code' => $consultant_code,
                'business_unit_code' => $business_unit_code,
                'client_code' => $client_code,
                'code' => $cost_center_code,
                'company_id' => $company_id,
                'created_at' => $date,
                'updated_at' => $date,
                'active' => 1
            ];
            $this->db->insert('tbl_workflow_cost_centers', $data);
        }
    }

    private function get_consultants()
    {
        $this->db->from('tbl_workflow_consultants');
        $result_consultants = $this->db->get()->result();

        $consultants = [];
        foreach ($result_consultants as $row) {
            $consultants[$row->company_id][$row->name] = $row;
        }

        return $consultants;
    }
}
