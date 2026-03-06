<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_columns_tbl_ubigeos_202410220959 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'code_zone' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ],
            'description_zone' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ],
        ];

        $this->dbforge->add_column('tbl_ubigeos', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_ubigeos', 'codigo_zona');
        $this->dbforge->drop_column('tbl_ubigeos', 'descripcion_zona');
    }
}
