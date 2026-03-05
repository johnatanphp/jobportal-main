<?php
class WS_sap_eecc_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($params = [])
    {
        $values = $this->search($params);

        $eecc_rows = [];

        foreach ($values as $row_value) {

            if (!isset($row_value->EXX_ADCT_ECC2Collection) || count($row_value->EXX_ADCT_ECC2Collection) == 0) {
                continue;
            }

            foreach ($row_value->EXX_ADCT_ECC2Collection as $row_collection_job) {
                
                $eecc_types = [
                    1 => 'Cotización',
                    2 => 'Estructura de Costos'
                ];

                $eecc_obj = new stdClass();
                $eecc_obj->eecc_code = $row_value->U_EXX_ADCT_CDCM; //Codigo EECC
                $eecc_obj->eecc_description = '';//$row_collection_job->U_EXX_ADCT_RHID;//$row_collection_job->U_EXX_ADCT_RHSC; //Descripcion EECC
                $eecc_obj->eecc_type_name = isset($eecc_types[$row_value->U_EXX_ADCT_TDEC]) ? $eecc_types[$row_value->U_EXX_ADCT_TDEC] : '-';
                $eecc_obj->form_id = $row_collection_job->U_EXX_ADCT_RHID; //Planilla ID
                $eecc_obj->job_code = $row_collection_job->U_EXX_ADCT_RHCC; //Codigo puesto
                $eecc_obj->job_name = $row_collection_job->U_EXX_ADCT_RHSC; //Nombre puesto
                $eecc_obj->job_vacancies = $row_collection_job->U_EXX_ADCT_RHCT; //Cantidad vacantes
            
                $eecc_rows[] = $eecc_obj;
            }
        }

        return $eecc_rows;

    }

    private function search($params = [])
    {
        $next_page = true;
        $page_limit = 200;
        $page_offset = 0;

        $result_values = [];

        while($next_page) {  
            $result = $this->get_data($params, $page_limit, $page_offset);

            $result_values = array_merge($result_values, $result->value ?? []);
           
            $next_page = isset($result->{"odata.nextLink"});
            $page_offset+=$page_limit;
        }

        return $result_values;
    }

    private function get_data($params, $page_limit, $page_offset)
    {
        if (!$this->config->item('sap_api_enabled')) {
            return [];
        }

        $parameters = [
            "U_EXX_ADCT_CDCS eq '" . ($this->get_consultant($params['cia_code'] ?? '0')) . "'", //Consultora codigo
            "U_EXX_ADCT_CDCL eq '" . ($params['client_code'] ?? '0') . "'", //Cliente codigo
            "U_EXX_ADCT_CDUN eq '" . ($params['business_unit_code'] ?? '0') . "'", //Unidad de negocio codigo
            "U_EXX_ADCT_ESTD eq '3'"
        ];

        $filter = join(' and ', $parameters);
        $company_id = $params['company_id'];

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

        $url = $sap_token->api_url . '/v1/EXX_ADCT_EECC?$skip=' . $page_offset . '&' . urlencode('$filter=' . $filter);

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

     //  curl_close($ch);

        if ($response === false) {
            return [];
        }

        return json_decode($response);
    }

    private function get_consultant($cia_code) 
    {
        $this->db->from('tbl_sap_workflow_consultants');
        $this->db->where('overall_cia_code', $cia_code);
        $row = $this->db->get()->row();

        return $row && $row->cia_code ? $row->cia_code : '0'; 
    }
}
