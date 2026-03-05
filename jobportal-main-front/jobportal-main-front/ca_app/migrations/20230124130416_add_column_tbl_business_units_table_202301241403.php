<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_business_units_table_202301241403 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_business_units DROP INDEX business_unit_name");

        $fields = [
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_business_units', $fields);

        $this->db->query("CREATE INDEX business_unit_name ON tbl_business_units (business_unit_name)");   
        $this->db->query("CREATE INDEX company_id ON tbl_business_units (company_id)");     
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_business_units', 'active');
        $this->dbforge->drop_colum('tbl_business_units', 'company_id');
    }
}
