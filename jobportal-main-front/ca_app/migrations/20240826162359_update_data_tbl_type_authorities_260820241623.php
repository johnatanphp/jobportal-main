<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_type_authorities_260820241623 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'id' => 1,
                'type_authority' => 'Director de Administración y Finanzas',
            ],
            [
                'id' => 2,
                'type_authority' => 'Gerente Administrativo',
            ],
            [
                'id' => 3,
                'type_authority' => 'Gerente / Jefe de Area',
            ],
            [
                'id' => 4,
                'type_authority' => 'GERENTE DE GESTIÓN HUMANA / JEFE',
            ],
        ];

        foreach ($data as $row) {
            $this->db->where('id', $row['id']);
            $query = $this->db->get('tbl_type_authorities');

            if ($query->num_rows() > 0) {
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_type_authorities', [
                    'type_authority' => $row['type_authority']
                ]);
            }
        }
    }

    public function down()
    {
        $data = [
            [
                'id' => 1,
                'type_authority' => 'DIRECTOR RESPONSABLE',
            ],
            [
                'id' => 2,
                'type_authority' => 'CONTROLLER FINANCIERO / VICEPRESIDENTE',
            ],
            [
                'id' => 3,
                'type_authority' => 'GERENTE / JEFE DEL ÁREA',
            ],
            [
                'id' => 4,
                'type_authority' => 'GERENTE DE GESTIÓN HUMANA / JEFE',
            ],
        ];

        foreach ($data as $row) {
            $this->db->where('id', $row['id']);
            $query = $this->db->get('tbl_type_authorities');

            if ($query->num_rows() > 0) {
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_type_authorities', [
                    'type_authority' => $row['type_authority']
                ]);
            }
        }    
    }
}
