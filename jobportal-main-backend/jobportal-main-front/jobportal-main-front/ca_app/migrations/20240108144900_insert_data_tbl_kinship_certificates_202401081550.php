<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_kinship_certificates_202401081550 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_kinship_certificates', [
            'id' => 12,
            'name' => 'ACTA DE NACIMIENTO O DOCUMENTO ANALOGO QUE SUSTENTA FILIACIÓN',
            'kinship_id' => 6,
            'iplani_code' => '10'
        ]);
    }

    public function down(){}
}
