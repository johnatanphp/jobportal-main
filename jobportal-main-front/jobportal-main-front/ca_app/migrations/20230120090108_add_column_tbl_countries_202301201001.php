<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_countries_202301201001 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'iso_3166_1_alpha2' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'unique' =>true,
            ],
            'iso_3166_1_alpha3' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'unique' =>true,
            ],
            'iso_3166_1_numeric' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => ''
            ],
            'coi' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => ''
            ],
            'fips_10' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => ''
            ],
            'vehicle_plate' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'default' => ''
            ],
            'domain' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => ''
            ]
        ];
        
        $this->dbforge->add_column('tbl_countries', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_countries', 'iso_3166_1_alpha2');
        $this->dbforge->drop_colum('tbl_countries', 'iso_3166_1_alpha3');
        $this->dbforge->drop_colum('tbl_countries', 'iso_3166_1_numeric');
        $this->dbforge->drop_colum('tbl_countries', 'coi');
        $this->dbforge->drop_colum('tbl_countries', 'fips_10');
        $this->dbforge->drop_colum('tbl_countries', 'vehicle_plate');
        $this->dbforge->drop_colum('tbl_countries', 'domain');       
    }
}
