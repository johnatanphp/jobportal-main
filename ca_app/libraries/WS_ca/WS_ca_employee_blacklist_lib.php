<?php
class WS_ca_employee_blacklist_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($document_numbers = [])
    {
        if (empty($document_numbers)) {
            return [];
        }
        
        $url = $this->config->item('ca_api_url') . '/Personal/Listar_TrabajadorNoContratable';

        $headers = [
            "Content-Type: application/json"
        ];

        $detail_document_number_search = [];

        foreach ($document_numbers as $document_number) {
            $detail_document_number_search[] = [
                "Numero_Documento_Identidad" => $document_number
            ];
        }

        $parameters = [
            "database" => $this->config->item('ca_api_database'),
            "Cod_Usuario" => $this->config->item('ca_api_cod_user'),
            "Detalle_Ceses" => $detail_document_number_search
        ];

        $curl_options = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($parameters),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->item('ca_api_auth_user') . ":" . $this->config->item('ca_api_auth_password'),
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        $response = curl_exec($ch);

        curl_close($ch);

        if ($response === false) {
            return [];
        }

        $results = (json_decode($response))->Mensaje ?? [];

        foreach ($results as $row_blacklist) {
            $obj_blacklist = new stdClass();
            $obj_blacklist->blacklist = $row_blacklist->Flg_No_Contratable == "S" ? 1 : 0;
            $obj_blacklist->blacklist_observation = $row_blacklist->Descripcio_Motivo;
            
            $result_blacklist[$row_blacklist->NumDocIdent_Colaborador] = $obj_blacklist;
        }

        return $result_blacklist;
    }
}
