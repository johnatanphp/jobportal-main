<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_workflow_consultants_202407241034 extends CI_Migration
{
    public function up() {
        // Datos obtenidos de la imagen, convertir esto a un array PHP
        $data = [
            ['CIA_NO_CIA' => '01', 'CIA_RUC' => '20122620434'],
            ['CIA_NO_CIA' => '02', 'CIA_RUC' => '20503630827'],
            ['CIA_NO_CIA' => '03', 'CIA_RUC' => '20504205232'],
            ['CIA_NO_CIA' => '04', 'CIA_RUC' => '20122620353'],
            ['CIA_NO_CIA' => '05', 'CIA_RUC' => '20257926371'],
            ['CIA_NO_CIA' => '07', 'CIA_RUC' => '20600251814'],
            ['CIA_NO_CIA' => '16', 'CIA_RUC' => '20543921255'],
            ['CIA_NO_CIA' => '17', 'CIA_RUC' => '20543919510'],
            ['CIA_NO_CIA' => '18', 'CIA_RUC' => '20544912551'],
            ['CIA_NO_CIA' => '21', 'CIA_RUC' => '20107205161'],
            ['CIA_NO_CIA' => '22', 'CIA_RUC' => '20600848497'],
        ];

        // Actualizar registros existentes e insertar nuevos registros si no existen
        foreach ($data as $company) {
            $this->db->where('code', $company['CIA_NO_CIA']);
            $query = $this->db->get('tbl_workflow_consultants');

            if ($query->num_rows() > 0) {
                // Si existe, actualizamos el registro
                $this->db->where('code', $company['CIA_NO_CIA']);
                $this->db->update('tbl_workflow_consultants', [
                    'ruc' => $company['CIA_RUC']
                ]);
            }
        }

        // Actualizar los registros que no coinciden para establecerlos en NULL
        $all_codes = array_column($data, 'CIA_NO_CIA');
        $this->db->where_not_in('code', $all_codes);
        $this->db->update('tbl_workflow_consultants', [
            'ruc' => ''
        ]);
    }

    public function down() {
        // Datos obtenidos de la imagen, convertir esto a un array PHP
        $data = [
            ['CIA_NO_CIA' => '01', 'CIA_RUC' => '20122620434'],
            ['CIA_NO_CIA' => '02', 'CIA_RUC' => '20503630827'],
            ['CIA_NO_CIA' => '03', 'CIA_RUC' => '20504205232'],
            ['CIA_NO_CIA' => '04', 'CIA_RUC' => '20122620353'],
            ['CIA_NO_CIA' => '05', 'CIA_RUC' => '20257926371'],
            ['CIA_NO_CIA' => '07', 'CIA_RUC' => '20600251814'],
            ['CIA_NO_CIA' => '16', 'CIA_RUC' => '20543921255'],
            ['CIA_NO_CIA' => '17', 'CIA_RUC' => '20543919510'],
            ['CIA_NO_CIA' => '18', 'CIA_RUC' => '20544912551'],
            ['CIA_NO_CIA' => '21', 'CIA_RUC' => '20107205161'],
            ['CIA_NO_CIA' => '22', 'CIA_RUC' => '20600848497'],
        ];

        // Revertir los cambios realizados en up(), es decir, establecer ruc como NULL
        $all_codes = array_column($data, 'CIA_NO_CIA');
        $this->db->where_in('code', $all_codes);
        $this->db->update('tbl_workflow_consultants', [
            'ruc' => ''
        ]);
    }
}
