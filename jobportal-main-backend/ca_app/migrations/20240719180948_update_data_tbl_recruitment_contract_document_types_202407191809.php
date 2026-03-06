<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_recruitment_contract_document_types_202407191809 extends CI_Migration
{
    public function up()
    {
        $data = [
            'name' => 'Documentos Laborales a Firmar'
        ];

        $this->db->where('name', 'GP-FO-004 Declaración jurada de información personal del trabajador');
        $this->db->update('tbl_recruitment_contract_document_types', $data);
    }

    public function down()
    {
        $data = [
            'name' => 'GP-FO-004 Declaración jurada de información personal del trabajador'
        ];

        $this->db->where('name', 'Documentos Laborales a Firmar');
        $this->db->update('tbl_recruitment_contract_document_types', $data);
    }
}
