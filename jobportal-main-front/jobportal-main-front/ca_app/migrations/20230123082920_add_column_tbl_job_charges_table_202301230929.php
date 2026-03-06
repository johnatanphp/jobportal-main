<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_charges_table_202301230929 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_job_charges DROP INDEX charge");

        $fields = [
            'country_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_job_charges', $fields);

        $this->db->query("CREATE INDEX country_id ON tbl_job_charges (country_id)");
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_charges', 'country_id');
    }
}
