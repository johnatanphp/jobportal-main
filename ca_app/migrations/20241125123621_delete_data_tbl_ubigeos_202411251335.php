<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Delete_data_tbl_ubigeos_202411251335 extends CI_Migration
{
    public function up()
    {
        $ubigeos = [
            ['Apurímac',	'Andahuaylas',	'José María Arguedas'],
            ['Apurímac',	'Chincheros',	'Rocchacc'],
            ['Apurímac',	'Chincheros',	'El Porvenir'],
            ['Ayacucho',	'Huamanga',	'Andrés Avelino Cáceres Dorregaray'],
            ['Ayacucho',	'Huanta',	'Canayre'],
            ['Ayacucho',	'Huanta',	'Uchuraccay'],
            ['Ayacucho',	'Huanta',	'Pucacolpa'],
            ['Ayacucho',	'Huanta',	'Chaca'],
            ['Ayacucho',	'La Mar',	'Samugari'],
            ['Ayacucho',	'La Mar',	'Anchihuay'],
            ['Callao',	'Prov. Const. del Callao',	'Mi Perú'],
            ['Cusco',	'La Convención',	'Inkawasi'],
            ['Cusco',	'La Convención',	'Villa Virgen'],
            ['Cusco',	'La Convención',	'Villa Kintiarina'],
            ['Huancavelica',	'Churcampa',	'Cosme'],
            ['Huancavelica',	'Tayacaja',	'Quichuas'],
            ['Huancavelica',	'Tayacaja',	'Andaymarca'],
            ['Huancavelica',	'Tayacaja',	'Roble'],
            ['Huancavelica',	'Tayacaja',	'Pichos'],
            ['Huánuco',	'Huánuco',	'Yacus'],
            ['Huánuco',	'Huánuco',	'San Pablo de Pillao'],
            ['Huánuco',	'Leoncio Prado',	'Pucayacu'],
            ['Huánuco',  'Leoncio Prado',	'Castillo Grande'],
            ['Huánuco',	'Marañón',	'La Morada'],
            ['Huánuco',	'Marañón',	'Santa Rosa de Alto Yanajanca'],
            ['Junín',	'Satipo',	'Vizcatan del Ene'],
            ['La Libertad',	'Gran Chimú',	'Lucma'],
            ['La Libertad',	'Gran Chimú',	'Marmot'],
            ['La Libertad',	'Gran Chimú',	'Sayapullo'],
            ['Loreto',	'Putumayo',	'Rosa Panduro'],
            ['Loreto',	'Putumayo',	'Yaguas'],
            ['Pasco',	'Oxapampa',	'Constitución'],
            ['Piura',	'Piura',	'Veintiseis de Octubre'],
            ['Tacna',	'Tacna',	'La Yarada los Palos'],
            ['Ucayali',	'Padre Abad',	'Neshuya'],
            ['Ucayali',	'Padre Abad',	'Alexander Von Humboldt']
        ];

        foreach ($ubigeos as $row_ubigeo) {

            $where_ubigeo = [
                'code_country' => 'PE',
                'order_administrative1' => trim($row_ubigeo[0]),
                'order_administrative2' => trim($row_ubigeo[1]),
                'order_administrative3' => trim($row_ubigeo[2]),
            ];

            $this->db->where($where_ubigeo);
            $this->db->delete('tbl_ubigeos');
        }
    }

    public function down(){}
}
