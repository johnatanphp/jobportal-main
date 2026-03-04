<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_stages_202507210935 extends CI_Migration
{
    public function up()
    {
        $this->dbforge->modify_column('tbl_recruitment_stages', [
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => FALSE
            ]
        ]);

        $this->db->where('id', 10);
        $this->db->update('tbl_recruitment_stages', [
            'name' => 'Programar entrevista psicológica'
        ]);
    }

    public function down(){}
}
