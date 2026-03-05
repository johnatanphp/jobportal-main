<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_exam_request_product_codes_202602041745 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PROTOCOLO TM3',
            'exam_type_id' => 1,
            'hrm_id' => 43
        ]);
        
        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PROTOCOLO TM6',
            'exam_type_id' => 1,
            'hrm_id' => 44
        ]);
        
        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PROTOCOLO XIAOMI 2',
            'exam_type_id' => 1,
            'hrm_id' => 45
        ]);
        
        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PROTOCOLO XIAOMI 3',
            'exam_type_id' => 1,
            'hrm_id' => 46
        ]);
    }

    public function down(){}
}
