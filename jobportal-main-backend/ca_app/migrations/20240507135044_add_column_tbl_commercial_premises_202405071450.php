<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_commercial_premises_202405071450 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'country_id' => [
                'type' => 'INT',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_commercial_premises', $fields);
        $this->db->query('ALTER TABLE tbl_commercial_premises ADD CONSTRAINT FOREIGN KEY (country_id) REFERENCES tbl_countries(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_commercial_premises', 'country_id');
    }
}
//