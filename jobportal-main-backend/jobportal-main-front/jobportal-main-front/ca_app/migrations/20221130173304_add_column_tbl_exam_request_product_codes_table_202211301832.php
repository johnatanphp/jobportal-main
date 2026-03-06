<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_exam_request_product_codes_table_202211301832 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'hrm_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_exam_request_product_codes', $fields);

        $this->insert_data();
    }

    public function insert_data() 
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->db->where('code_product', $i);
            $this->db->update('tbl_exam_request_product_codes', ['hrm_id' => $i]);
        }

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA RAPIDA',
            'exam_type_id' => 2,
            'hrm_id' => 11
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA MOLECULAR PCR',
            'exam_type_id' => 2,
            'hrm_id' => 12
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA ANTIGENO',
            'exam_type_id' => 2,
            'hrm_id' => 13
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA ELISA',
            'exam_type_id' => 2,
            'hrm_id' => 14
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA ELECTROQUIMIOLUMINISCENCIA',
            'exam_type_id' => 2,
            'hrm_id' => 15
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA CLIA',
            'exam_type_id' => 2,
            'hrm_id' => 16
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA ECLIA',
            'exam_type_id' => 2,
            'hrm_id' => 17
        ]);

        $this->db->insert('tbl_exam_request_product_codes', [
            'cod_portal' => 'PRUEBA IFI',
            'exam_type_id' => 2,
            'hrm_id' => 18
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_exam_request_product_codes', 'hrm_id');
    }
}
