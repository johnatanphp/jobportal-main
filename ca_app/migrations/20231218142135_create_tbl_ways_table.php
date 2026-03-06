<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_ways_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => false,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'active' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_ways');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_ways');
    }

    private function insert_data()
    {
        $ways = [
            '01' =>	'Avenida',
            '02' => 'Jiron',
            '03' => 'Calle',
            '04' => 'Pasaje',
            '05' => 'Alameda',
            '06' => 'Malecón',
            '07' => 'Ovalo',
            '08' => 'Parque',
            '09' => 'Plaza',
            '10' => 'Carretera',
            '13' => 'TROCHA',
            '14' => 'CAMINO RURAL',
            '15' => 'BAJADA',
            '16' => 'GALERIA',
            '17' => 'PROLONGACIÓN', 
            '18' => 'PASEO',
            '19' => 'PLAZUELA',
            '20' => 'PORTAL',
            '21' => 'CAMINO AFIRMADO',
            '22' => 'TROCHA CARROZABLE',
            '99' => 'OTROS'
        ];

        foreach ($ways as $id => $row) {
            $this->db->insert('tbl_ways', [
                'id' => $id,
                'name' => $row
            ]);
        }
    }
}
