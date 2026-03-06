<?php

class WS_hrmgo_workflow_overall_lib
{
	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function migrate($company_ids)
    {
        $next_page = true;
        $page_offset = 1;

        $this->db->from('tbl_hrmgo_api_conections');
        $this->db->where_in('company_id', $company_ids);
        $this->db->where('active', 1);
        $results = $this->db->get()->result();

        foreach ($results as $row) {        
            while($next_page) {  
                $result = $this->get_data($row->company_id, $page_offset);
                $this->migrate_workflow($result->data ?? [], $row->company_id);
                $next_page = isset($result->links->next);
                echo $page_offset . "\n";
                $page_offset++;
            }

            $next_page = true;
            $page_offset = 1;
        }
    }
    
    private function get_data($company_id, $page_offset)
    {       
        $this->load->library('WS_hrmgo/WS_hrmgo_api_token_lib');

        $hrmgo_api = $this->ws_hrmgo_api_token_lib->get_token($company_id);

        if (!$hrmgo_api) {
            return [];
        }

        $token = $hrmgo_api->token;
        $headers = [
			"Authorization: Bearer " . $token
        ];

        $url = $hrmgo_api->api_url . '/cost_center?page=' . $page_offset;

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
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
    
    public function migrate_workflow($result_data, $company_id)
    {
        foreach ($result_data as $row) {
            $this->migrate_consultant($row->branch, $company_id);
            $this->migrate_client($row->businessPartner, $company_id);
            $this->migrate_business_unit($row->businessUnit, $company_id);
            $this->migrate_cost_center($row, $company_id);
        }
    }

    public function migrate_consultant($data, $company_id)
    {
        $consultant_code = trim($data->code);
        $consultant_name = trim($data->name);
        
        if (!$consultant_code) {
            return;
        }   

        $cc = $this->db->get_where('tbl_workflow_consultants', [
            'code' => $consultant_code,
            'company_id' => $company_id
        ])->row();
            
        if ($cc) {

            $data = [
                'active' => 1
            ];

            $this->db->where('id', $cc->id);
            $this->db->update('tbl_workflow_consultants', $data);
            
            return;
        }

        $data = [
            'code' => $consultant_code,
            'name' => $consultant_name,
            'company_id' => $company_id,
            'active' => 1
        ];
        $this->db->insert('tbl_workflow_consultants', $data);   
    }

    public function migrate_client($data, $company_id)
    {
        $client_code = trim($data->code);
        $client_name = trim($data->business_name);
        
        if (!$client_code) {
            return;
        }   

        $cc = $this->db->get_where('tbl_workflow_clients', [
            'code' => $client_code,
            'company_id' => $company_id
        ])->row();
            
        if ($cc) {

            $data = [
                'active' => 1
            ];

            $this->db->where('id', $cc->id);
            $this->db->update('tbl_workflow_clients', $data);
            
            return;
        }

        $data = [
            'code' => $client_code,
            'name' => $client_name,
            'company_id' => $company_id,
            'active' => 1
        ];
        $this->db->insert('tbl_workflow_clients', $data);   
    }

    public function migrate_business_unit($data, $company_id)
    {
        $business_unit_code = trim($data->code);
        $business_unit_name = trim($data->description);
        
        if (!$business_unit_code) {
            return;
        }   

        $cc = $this->db->get_where('tbl_business_units', [
            'business_unit_code' => $business_unit_code,
            'company_id' => $company_id
        ])->row();
            
        if ($cc) {

            $data = [
                'active' => 1
            ];

            $this->db->where('id', $cc->ID);
            $this->db->update('tbl_business_units', $data);
            
            return;
        }

        $data = [
            'business_unit_code' => $business_unit_code,
            'business_unit_name' => $business_unit_name,
            'company_id' => $company_id,
            'active' => 1
        ];
        $this->db->insert('tbl_business_units', $data);   
    }

    public function migrate_cost_center($data, $company_id)
    {
        $date = date('Y-m-d H:i:s');

        $consultant_code = trim($data->branch->code);
        $cost_center_code = trim((string)$data->code);
        $client_code = trim($data->businessPartner->code);
        $business_unit_code = trim($data->businessUnit->code);

        if (!$consultant_code || 
            !$client_code || 
            !$business_unit_code || 
            !$cost_center_code) {
            return;
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
            
            return;
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
