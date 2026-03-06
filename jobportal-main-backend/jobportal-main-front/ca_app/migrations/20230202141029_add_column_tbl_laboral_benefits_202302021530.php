<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_laboral_benefits_202302021530 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_laboral_benefits DROP INDEX benefit_name");

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

        $this->dbforge->add_column('tbl_laboral_benefits', $fields);

        $this->db->query("CREATE INDEX company_id ON tbl_laboral_benefits (company_id)"); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_laboral_benefits', 'company_id');
    }
}
