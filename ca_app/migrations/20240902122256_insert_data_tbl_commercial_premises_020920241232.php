<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_commercial_premises_020920241232 extends CI_Migration
{
    public function up()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Plaza Vea - Av Caveneccia 234, San Isidro, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 2,
                'name' => 'Plaza Vea - Av La Marina 234, San Miguel, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 3,
                'name' => 'Wong - Av Benavides 100, Miraflores, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 4,
                'name' => 'Metro - Av Arenales 450, Lince, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 5,
                'name' => 'Tottus - Av Primavera 750, Surco, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 6,
                'name' => 'Vivanda - Av Pardo y Aliaga 640, San Isidro, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 7,
                'name' => 'Plaza Vea - Av Brasil 800, Pueblo Libre, Lima, Lima',
                'country_id' => 56
            ],
            [
                'id' => 8,
                'name' => 'Wong - Av Angamos 1200, Surquillo, Lima, Lima',
                'country_id' => 56
            ],
        ];

        foreach ($data as $row) {
            // Verificar si el registro ya existe
            $this->db->where('id', $row['id']);
            $query = $this->db->get('tbl_commercial_premises');

            if ($query->num_rows() > 0) {
                $this->db->where('id', $row['id']);
                $this->db->update('tbl_commercial_premises', [
                    'name' => $row['name'],
                    'country_id' => $row['country_id'],
                ]);
            } else {
                $this->db->insert('tbl_commercial_premises', $row);
            }
        }
    }

    public function down()
    {
        $this->db->where_in('id', [1, 2, 3, 4, 5, 6, 7, 8]);
        $this->db->delete('tbl_commercial_premises');
    }
}
