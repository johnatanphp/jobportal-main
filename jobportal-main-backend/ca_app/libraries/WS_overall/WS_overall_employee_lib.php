<?php
class WS_overall_employee_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function search($query)
    {
        $url = 'https://app.overall.pe/WSOverallServices/General/ListarTrabajador';
        $apiKey = $this->config->item('eplani_api_key');
        $query = trim($query);
        $employees = array();

        if (is_numeric($query)) {
            $results = $this->curl_lib->exec($url, 'POST', array(
                'apiKey' => $apiKey,
                'dni' => $query
            ));

            $employees = $this->format_data_results($results);

            return $employees;
        }
        
        $query_part = explode(' ', $query);
        $query_part[1] = empty($query_part[1]) ? $query_part[0]: $query_part[1];   
        
        $results = $this->curl_lib->exec($url, 'POST', array(
            'apiKey' => $apiKey,
            'primer_nombre' => $query_part[0]
        ));

        $employees+=$this->format_data_results($results);

        $results = $this->curl_lib->exec($url, 'POST', array(
            'apiKey' => $apiKey,
            'ap_paterno' => $query_part[1]
        ));

        $employees+=$this->format_data_results($results);

        return $employees;
    }

    private function format_data_results($results)
    {
        $results = json_decode($results, true);
        $employees = array();

        if ($results['MESSAGE'] != 'OK') {
            return $employees;
        }

        if (isset($results['TRABAJADOR'])) {
            $results = $results['TRABAJADOR'];
        }

        //Limitar resultados a 100 registros
        foreach ($results as $row) {
            $employees[$row['DNI']] = $row;
        }

        return $employees;
    }
}
