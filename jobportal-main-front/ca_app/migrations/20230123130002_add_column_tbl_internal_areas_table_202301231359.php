<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_internal_areas_table_202301231359 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_internal_areas DROP INDEX area_name");

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

        $this->dbforge->add_column('tbl_internal_areas', $fields);
        $this->db->query("CREATE INDEX country_id ON tbl_internal_areas (country_id)");  
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_internal_areas', 'active');
        $this->dbforge->drop_colum('tbl_internal_areas', 'country_id');
    }
}
