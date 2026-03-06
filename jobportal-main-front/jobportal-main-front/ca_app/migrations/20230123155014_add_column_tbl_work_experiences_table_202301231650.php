<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_work_experiences_table_202301231650 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_work_experiences', $fields);
        $this->db->query("CREATE INDEX country_id ON tbl_work_experiences (country_id)");     
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_work_experiences', 'country_id');
    }
}
