<?php

class WS_overall_eecc_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function all($parameters = [])
    {
        $url = 'https://app.overall.pe/UserRestApi/api/master/cotizacion';

        $params = [
            'nom_user' => 'ADMIN',
            'cod_cia' => $parameters['cia_code'],
            'cod_ccosto' => $parameters['cost_center'],
            'cod_clie' => $parameters['client_code'],
            'cod_coti' => '',
            'periodo' => date('01/m/Y'),
            'tipo_ord' => '2'
        ];

        $result = json_decode($this->curl_lib->exec($url, 'POST', $params));
        $result = $result->data ?? [];

        $data = [];

        foreach ($result as $row) {

            $eecc_obj = new stdClass();
            $eecc_obj->eecc_code = $row->cod_coti;
            $eecc_obj->eecc_description = $row->descripcion;

            $data[] = $eecc_obj;
        }

        return $data;
    }
}
