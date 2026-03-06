<?php

class Workflow_overall_update_lib
{
	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec()
    {
        $this->insert_cost_centers();
        $this->insert_consultants();
        $this->insert_clients();
        $this->insert_areas();
    }
 
    public function insert_consultants()
    {
        $url = 'https://app.overall.pe/WSOverallServices/Reclutamiento/ListarConsultora';
        $params = array(
            'apiKey' => $this->config->item('eplani_api_key')
        );

        $json = json_decode($this->curl_lib->exec($url, 'POST', $params));

        $results = isset($json->CONSULTORA) ? $json->CONSULTORA : [];

        $company_ids = explode(',', $this->config->item('workflow_migration_company_ids'));
        
        foreach ($company_ids as $company_id) {

            if (empty($company_id)) {
                continue;
            }

            foreach ($results as $row) {

                $consultant = $this->db->get_where('tbl_workflow_consultants', [
                    'code' => $row->NO_CIA,
                    'company_id' => $company_id
                ])->row();

                if ($consultant && $consultant->active) {
                    continue;
                }
    
                if ($consultant) {
    
                    $data = [
                        'code' => $row->NO_CIA,
                        'name' => $row->CONSULTORA,
                        'active' => 1
                    ];
    
                    $this->db->where('id', $consultant->id);
                    $this->db->update('tbl_workflow_consultants', $data);
                    
                    continue;
                }
    
                $data = [
                    'code' => $row->NO_CIA,
                    'name' => $row->CONSULTORA,
                    'active' => 1,
                    'company_id' => $company_id
                ];
                $this->db->insert('tbl_workflow_consultants', $data);
            }
        }
    }

    public function insert_clients()
    {
        $url = 'https://app.overall.pe/WSOverallServices/Reclutamiento/ListarCliente';
        $params = array(
            'apiKey' => $this->config->item('eplani_api_key')
        );

        $json = json_decode($this->curl_lib->exec($url, 'POST', $params));

        $results = isset($json->CLIENTE) ? $json->CLIENTE : [];

        $company_ids = explode(',', $this->config->item('workflow_migration_company_ids'));

        foreach ($company_ids as $company_id) {

            if (empty($company_id)) {
                continue;
            }

            foreach ($results as $row) {

                $client = $this->db->get_where('tbl_workflow_clients', [
                    'code' => $row->COD_CLIE,
                    'company_id' => $company_id
                ])->row();
                    
                if ($client && $client->active) {
                    continue;
                }

                if ($client) {
                    
                    $data = [
                        'code' => $row->COD_CLIE,
                        'name' => $row->CLIENTE,
                        'active' => 1
                    ];
    
                    $this->db->where('id', $client->id);
                    $this->db->update('tbl_workflow_clients', $data);
                    
                    continue;
                }
    
                $data = [
                    'code' => $row->COD_CLIE,
                    'name' => $row->CLIENTE,
                    'active' => 1,
                    'company_id' => $company_id
                ];
                $this->db->insert('tbl_workflow_clients', $data);
            }
        }
    }

    public function insert_cost_centers()
    {
        $url = 'https://app.overall.pe/WSOverallServices/Reclutamiento/ListarCentroCosto';
        $params = array(
            'apiKey' => $this->config->item('eplani_api_key')
        );

        $json = json_decode($this->curl_lib->exec($url, 'POST', $params));

        $results = isset($json->CENTROCOSTO) ? $json->CENTROCOSTO : [];

        $company_ids = explode(',', $this->config->item('workflow_migration_company_ids'));

        foreach ($company_ids as $company_id) {

            if (empty($company_id)) {
                continue;
            }
            
            foreach ($results as $row) {

                $cc = $this->db->get_where('tbl_workflow_cost_centers', [
                    'cia_code' => $row->NO_CIA,
                    'business_unit_code' => $row->UNI_NEG,
                    'client_code' => $row->COD_CLIE,
                    'code' => $row->COD_CCOSTO,
                    'company_id' => $company_id
                ])->row();
                    
                if ($cc && $cc->active) {
                    continue;
                }

                if ($cc) {
    
                    $data = [
                        'code' => $row->COD_CCOSTO,
                        'active' => 1
                    ];
    
                    $this->db->where('id', $cc->id);
                    $this->db->update('tbl_workflow_cost_centers', $data);
                    
                    continue;
                }
    
                $data = [
                    'cia_code' => $row->NO_CIA,
                    'business_unit_code' => $row->UNI_NEG,
                    'client_code' => $row->COD_CLIE,
                    'code' => $row->COD_CCOSTO,
                    'active' => 1,
                    'company_id' => $company_id
                ];
                $this->db->insert('tbl_workflow_cost_centers', $data);
            }
        }
    }

    public function insert_areas()
    {
        $url = $this->config->item('hrm_api_url') . '/workflow_areas/listar';
        $params = [
            'api_key' => $this->config->item('hrm_api_key')
        ];

        $json = json_decode($this->curl_lib->exec($url, 'GET', $params));

        $results = isset($json->data) ? $json->data : [];

        $company_ids = [1];

        foreach ($company_ids as $company_id) {

            if (empty($company_id)) {
                continue;
            }
            
            foreach ($results as $row) {

                $area = $this->db->get_where('tbl_workflow_areas', [
                    'cia_code' => $row->cia_no_cia,
                    'code' => $row->area_cod_ccosto,
                    'company_id' => $company_id
                ])->row();
                    
                if ($area) {
    
                    $data = [
                        'name' => $row->area_nom_centro,
                        'active' => 1
                    ];
    
                    $this->db->where('id', $area->id);
                    $this->db->update('tbl_workflow_areas', $data);
                    
                    continue;
                }
    
                $data = [
                    'cia_code' => $row->cia_no_cia,
                    'code' => $row->area_cod_ccosto,
                    'name' => $row->area_nom_centro,
                    'active' => 1,
                    'company_id' => $company_id
                ];
                $this->db->insert('tbl_workflow_areas', $data);
            }
        }
    }
}
