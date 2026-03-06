<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_ubigeos_202405031045 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true,
                'after' => 'id'
            ],
            'order_administrative1_code' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => TRUE,
                'after' => 'country_id'
            ],
            'order_administrative2_code' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => TRUE,
                'after' => 'order_administrative1'
            ],
            'order_administrative3_code' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => TRUE,
                'after' => 'order_administrative2'
            ],
            'lat' => [
                'type' => 'DECIMAL',
                'constraint' => '17,14',
                'null' => TRUE,
            ],
            'lng' => [
                'type' => 'DECIMAL',
                'constraint' => '17,14',
                'null' => TRUE,
            ],
        ];

        $this->dbforge->add_field('id INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        $this->dbforge->add_column('tbl_ubigeos', $fields); 
        $this->db->query('ALTER TABLE tbl_ubigeos ADD CONSTRAINT FOREIGN KEY (country_id) REFERENCES tbl_countries(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_ubigeos', 'id');
        $this->dbforge->drop_colum('tbl_ubigeos', 'country_id');
        $this->dbforge->drop_colum('tbl_ubigeos', 'order_administrative1_code');
        $this->dbforge->drop_colum('tbl_ubigeos', 'order_administrative2_code');
        $this->dbforge->drop_colum('tbl_ubigeos', 'order_administrative3_code');
        $this->dbforge->drop_colum('tbl_ubigeos', 'lat');
        $this->dbforge->drop_colum('tbl_ubigeos', 'lng');
    }
}
//