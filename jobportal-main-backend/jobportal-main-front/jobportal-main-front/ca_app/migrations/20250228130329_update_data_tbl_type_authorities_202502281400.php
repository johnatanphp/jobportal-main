<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_type_authorities_202502281400 extends CI_Migration
{
    public function up()
    {
        $this->db->where('ID', 1);
        $this->db->update('tbl_type_authorities', [
            'type_authority' => 'Aprobador de Unidad de Negocio'
        ]);
    }

    public function down(){}
}
