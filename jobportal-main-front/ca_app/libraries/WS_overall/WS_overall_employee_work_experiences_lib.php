<?php

class WS_overall_employee_work_experiences_lib 
{   
    public function __construct(){}

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($document_number = [])
    {
        $url = 'https://app.overall.pe/WSOverallServices/General/ListarTrabajadorDatosLaboralesHistorial';
        $params = [
            'apiKey' => $this->config->item('eplani_api_key'),
            'DNI' => join(',', $document_number)
        ];

        $result = json_decode($this->curl_lib->exec($url, 'POST', $params));
        
        //dd(join(',', $document_number));
        $experiences = [];
        
        if (!empty($result->TRABAJADOR_DATOSLABORALES)) {
            $employee_experiences = $result->TRABAJADOR_DATOSLABORALES;
            
            foreach ($employee_experiences as $row_exp) {

                $experience_obj = new stdClass();
                $experience_obj->employee_document_number = $row_exp->DNI;
                $experience_obj->consultant_code = $row_exp->NO_CIA;
                $experience_obj->consultant_name = $row_exp->DES_CIA;
                $experience_obj->client_code = $row_exp->COD_CLIE;
                $experience_obj->client_name = $row_exp->DES_CLIENTE;
                $experience_obj->date_admission = $row_exp->FEC_INGRESO;
                $experience_obj->date_termination = $row_exp->FEC_CESE;
                $experience_obj->employee_status = $row_exp->EST_TRABAJADOR;
                $experience_obj->sheet_name = $row_exp->DES_PLANILLA;
                
                $experiences[$row_exp->DNI][] = $experience_obj;
            }
        }
        
        return $experiences;
    }
}
