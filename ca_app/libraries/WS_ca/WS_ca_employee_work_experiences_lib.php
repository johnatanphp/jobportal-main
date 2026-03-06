<?php

class WS_ca_employee_work_experiences_lib 
{   
    public function __construct(){}

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($document_numbers = [])
    {
        $url = $this->config->item('ca_api_url') . "/Personal/Listar_PersonalHistorialCambios";

        $parameters = [
            "database" => $this->config->item('ca_api_database'),
            "usuario" => $this->config->item('ca_api_cod_user'),
            "numero_docident" => join(',', $document_numbers)
        ];

		$curl_options = [
			CURLOPT_URL => $url.= "?" . http_build_query($parameters),
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
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

        $response_api = json_decode($response);

        $experiences = [];

        foreach ($response_api as $row) {
            if (!isset($row->NumDocIdent_Colaborador)) {
                continue;
            }

            $doc_number = $row->NumDocIdent_Colaborador;
            $historical = $row->Historial_Cambios;

            foreach ($historical as $row_exp) {

                $experience_obj = new stdClass();
                $experience_obj->employee_document_number = $doc_number;
                $experience_obj->employee_status = $row_exp->Fecha_Cese == '' ? 'ACTIVO' : 'CESADO';
                $experience_obj->consultant_code = $row_exp->Cod_Empresa;
                $experience_obj->consultant_name = $row_exp->Empresa;
                $experience_obj->client_code = $row_exp->Cod_Proyecto;
                $experience_obj->client_name = $row_exp->Proyecto;
                $experience_obj->date_admission = $row_exp->Fecha_Ingreso;
                $experience_obj->date_termination = $row_exp->Fecha_Cese;
                $experience_obj->sheet_name = $row_exp->Planilla;
                
                $experiences[$doc_number][] = $experience_obj;
            }
        }   
  
        return $experiences;
    }
}
