<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_job_experiences_290820241500 extends CI_Migration
{
    public function up()
    {
        $data = [
            ['id' => 1, 'name' => 'Canal Moderno - AASS (autoservicio)', 'active' => 1],
            ['id' => 2, 'name' => 'Cadena (Tiendas por conveniencia)', 'active' => 1],
            ['id' => 3, 'name' => 'Canal Tradicional', 'active' => 1],
            ['id' => 4, 'name' => 'Canal Farma', 'active' => 1],
            ['id' => 5, 'name' => 'Canal Perfumero', 'active' => 1],
            ['id' => 6, 'name' => 'Canal Retail (Tiendas por departamento)', 'active' => 1],
            ['id' => 7, 'name' => 'Canal Horizontal (Bodegas)', 'active' => 1],
        ];

        foreach ($data as $row) {
            // Si el registro existe (por id), actualizarlo
            $this->db->where('id', $row['id']);
            $exists = $this->db->get('tbl_job_experiences')->num_rows() > 0;

            if ($exists) {
                // Actualizar registro existente
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_job_experiences', [
                    'name' => $row['name'],
                    'active' => $row['active']
                ]);
            } else {
                // Insertar nuevo registro
                $this->db->insert('tbl_job_experiences', $row);
            }
        }
    }

    public function down()
    {
        $data = [
            ['id' => 1, 'name' => 'Autoservicios', 'active' => 1],
            ['id' => 2, 'name' => 'Tiendas por departamento', 'active' => 1],
        ];

        // Reemplazar o insertar los datos anteriores
        foreach ($data as $row) {
            // Si el registro existe (por id), actualizarlo
            $this->db->where('id', $row['id']);
            $exists = $this->db->get('tbl_job_experiences')->num_rows() > 0;

            if ($exists) {
                // Actualizar registro existente
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_job_experiences', [
                    'name' => $row['name'],
                    'active' => $row['active']
                ]);
            } else {
                // Insertar nuevo registro
                $this->db->insert('tbl_job_experiences', $row);
            }
        }
    }
}
