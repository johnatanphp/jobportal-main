<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_exam_request_product_codes_202512041305 extends CI_Migration
{
    public function up()
    {
        $this->db->from('tbl_exam_request_product_codes');
        $this->db->where('code_product', 23);
        $product = $this->db->get()->row();

        if (!$product) {
            $this->db->insert('tbl_exam_request_product_codes', [
                'code_product' => 23,
                'cod_portal' => 'PROTOCOLO 11 - PROTOCOLO TM1',
                'exam_type_id' => 1,
                'hrm_id' => 38
            ]);
        }

        $this->db->from('tbl_exam_request_product_codes');
        $this->db->where('code_product', 24);
        $product = $this->db->get()->row();

        if (!$product) {
            $this->db->insert('tbl_exam_request_product_codes', [
                'code_product' => 24,
                'cod_portal' => 'PROTOCOLO 12 - PROTOCOLO XIAOMI 1',
                'exam_type_id' => 1,
                'hrm_id' => 39
            ]);
        }

        $this->db->from('tbl_exam_emo_types');
        $this->db->where('id', 23);
        $product = $this->db->get()->row();

        if (!$product) {
            $this->db->insert('tbl_exam_emo_types', [
                'id' => 23,
                'name' => 'PROTOCOLO 11 - PROTOCOLO TM1',
                'product_code' => '0008249',
                'active' => 1
            ]);
        }

        $this->db->from('tbl_exam_emo_types');
        $this->db->where('id', 24);
        $product = $this->db->get()->row();

        if (!$product) {
            $this->db->insert('tbl_exam_emo_types', [
                'id' => 24,
                'name' => 'PROTOCOLO 12 - PROTOCOLO XIAOMI 1',
                'product_code' => '0008249',
                'active' => 1
            ]);
        }
    }

    public function down(){}
}
