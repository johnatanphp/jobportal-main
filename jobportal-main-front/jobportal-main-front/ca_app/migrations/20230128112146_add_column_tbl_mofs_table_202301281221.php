<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_mofs_table_202301281221 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_mofs', $fields);

        $this->db->query("CREATE INDEX company_id ON tbl_mofs (company_id)");  
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_mofs', 'company_id');
    }
}
