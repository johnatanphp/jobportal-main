<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_chains_202405071420 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'country_id' => [
                'type' => 'INT',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_chains', $fields);
        $this->db->query('ALTER TABLE tbl_chains ADD CONSTRAINT FOREIGN KEY (country_id) REFERENCES tbl_countries(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_chains', 'country_id');
    }
}
//