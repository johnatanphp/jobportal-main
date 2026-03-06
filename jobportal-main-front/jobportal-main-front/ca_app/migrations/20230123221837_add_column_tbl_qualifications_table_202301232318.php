<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_qualifications_table_202301232318 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ],
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_qualifications', $fields);
        $this->db->query("CREATE INDEX country_id ON tbl_qualifications (country_id)");
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_qualifications', 'active');
        $this->dbforge->drop_colum('tbl_qualifications', 'country_id');
    }
}
