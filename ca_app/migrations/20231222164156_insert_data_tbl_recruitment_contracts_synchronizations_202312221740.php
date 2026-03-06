<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_recruitment_contracts_synchronizations_202312221740 extends CI_Migration
{
    public function up()
    {
        $data = [
            [7, 'Certificado laboral', 1],
            [8, 'Doc. Identidad Familiar', 1],
            [9, 'Certificado Familiar', 1],
            [10, 'Certificado de estudios', 0],
            [11, 'Permiso para firma de contrato', 0],
            [12, 'Otros documentos', 1],
        ];

        foreach ($data as $row) {
            $this->db->insert('tbl_recruitment_contracts_synchronizations', [
                'id' => $row[0],
                'name' => $row[1],
                'active' => $row[2]
            ]);
        }
    }

    public function down(){}
}
