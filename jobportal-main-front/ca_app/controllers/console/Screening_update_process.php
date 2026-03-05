<?php
require_once ("App_console.php");

class Screening_update_process extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select('id, response');
        $this->db->from('tbl_screening');
        $this->db->where('its_cron_update', false);
        $this->db->limit(30);

        $results = $this->db->get()->result_array();

        // Verificación si $results está vacío
        if (empty($results)) {
            echo "No se encontraron registros para actualizar process en tbl_screening";
            return;
        }

        // Recorre cada registro para verificar y actualizar los campos
        foreach ($results as $row) {
            $response = json_decode($row['response'], true);
            $hasFiscalia = false;

            // Verifica si `Fiscalia` existe dentro de la estructura anidada
            if (
                isset($response['data']['data']) && 
                is_array($response['data']['data']) && 
                isset($response['data']['data'][0]['Fiscalia']) && 
                !empty($response['data']['data'][0]['Fiscalia'])
            ) {
                $hasFiscalia = true;
            }

            $this->db->where('id', $row['id']);
            $this->db->update('tbl_screening', [
                'its_cron_update' => true,
                'its_data_prosecution' => $hasFiscalia
            ]);
        }
    }
}
