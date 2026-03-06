<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_recruitment_contracts_synchronizations_202505051600 extends CI_Migration
{
    public function up()
    {
        $data = [
            [13, 'Registrar Intake HRM GO', 1],
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
