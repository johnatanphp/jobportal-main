<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_request_authorities_202301302055 extends CI_Migration
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

        $this->dbforge->add_column('tbl_staff_request_authorities', $fields);

        $this->db->query("CREATE INDEX company_id ON tbl_staff_request_authorities (company_id)"); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_request_authorities', 'company_id');
    }
}
