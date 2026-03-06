<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_exam_emo_types_202602041800 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_exam_emo_types', [
            'id' => 25,
            'name' => 'PROTOCOLO TM3',
            'active' => 1
        ]);
        
        $this->db->insert('tbl_exam_emo_types', [
            'name' => 'PROTOCOLO TM6',
            'id' => 26,
            'active' => 1
        ]);
        
        $this->db->insert('tbl_exam_emo_types', [
            'name' => 'PROTOCOLO XIAOMI 2',
            'id' => 27,
            'active' => 1
        ]);
        
        $this->db->insert('tbl_exam_emo_types', [
            'name' => 'PROTOCOLO XIAOMI 3',
            'id' => 28,
            'active' => 1
        ]);
    }

    public function down(){}
}
