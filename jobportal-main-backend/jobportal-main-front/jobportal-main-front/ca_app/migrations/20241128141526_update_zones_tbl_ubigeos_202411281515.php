<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_zones_tbl_ubigeos_202411281515 extends CI_Migration
{
    public function up()
    {
        $zones = [
            [
                'id' => 1,
                'name' => 'Lima Norte',
                'departament' => 'Lima',
                'districts' => [
                    'Ancón', 
                    'Carabayllo', 
                    'Comas', 
                    'Independencia', 
                    'Los Olivos',
                    'Puente Piedra', 
                    'San Martín de Porres',
                    'Santa Rosa'
                ]
            ],
            [
                'id' => 2,
                'name' => 'Lima Centro',
                'departament' => 'Lima',
                'districts' => [
                    'Breña', 
                    'La Victoria', 
                    'Lima',
                    'Rímac',
                    'San Luis'
                ]
            ],
            [
                'id' => 3,
                'name' => 'Lima Oeste',
                'departament' => 'Lima',
                'districts' => [
                    'Barranco', 
                    'Jesús María', 
                    'La Molina', 
                    'Lince', 
                    'Magdalena del Mar', 
                    'Miraflores', 
                    'Pueblo Libre', 
                    'San Borja', 
                    'San Isidro', 
                    'San Miguel',
                    'Santiago de Surco',
                    'Surquillo'
                ]
            ],
            [
                'id' => 4,
                'name' => 'Lima Este',
                'departament' => 'Lima',
                'districts' => [
                    'Ate',
                    'Chaclacayo',
                    'Cieneguilla',
                    'El Agustino',
                    'Lurigancho', 
                    'San Juan de Lurigancho',
                    'Santa Anita'
                ]
            ],
            [
                'id' => 5,
                'name' => 'Lima Sur',
                'departament' => 'Lima',
                'districts' => [
                    'Chorrillos', 
                    'Lurín', 
                    'Pachacamac', 
                    'Pucusana', 
                    'Punta Hermosa', 
                    'Punta Negra', 
                    'San Bartolo', 
                    'San Juan de Miraflores', 
                    'Santa María del Mar',
                    'Villa El Salvador',
                    'Villa María del Triunfo'
                ]
            ],
            [
                'id' => 6,
                'name' => 'Callao',
                'departament' => 'Callao',
                'districts' => [
                    'Bellavista', 
                    'Callao', 
                    'Carmen de la Legua', 
                    'La Perla', 
                    'La Punta', 
                    'Ventanilla',
                    'Mi Perú'
                ]
            ]
        ];

        $this->db->where('1=1');
        $this->db->update('tbl_ubigeos', [
            'code_zone' => null,
            'description_zone' => null
        ]);

        foreach ($zones as $zone) {

            $districts = $zone['districts'];
            $departament = trim($zone['departament']);

            foreach ($districts as $district) {

                $district = mb_strtoupper($district);
            
                $district_search = str_replace(
                    ['Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['A', 'E', 'I', 'O', 'U'],
                    $district
                );

                $this->db->where('order_administrative1', $departament);
                $this->db->where('order_administrative3', $district_search);
                $this->db->update('tbl_ubigeos', [
                    'code_zone' => $zone['id'],
                    'description_zone' => $zone['name']
                ]);
            }
        }
    }

    public function down(){}
}
