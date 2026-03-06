<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_kinship_certificates_202401081510 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'active' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 1
            ]
        ];

        $this->dbforge->add_column('tbl_kinship_certificates', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_kinship_certificates', 'active');
    }
}
