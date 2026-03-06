<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_kinship_202401081500 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'active' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 1
            ]
        ];

        $this->dbforge->add_column('tbl_kinship', $fields); 

        $this->update_data();
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_kinship', 'active');
    }

    public function update_data()
    {
        $this->db->where('id', 3);
        $this->db->update('tbl_kinship', [
            'active' => 0
        ]);
    }
}
