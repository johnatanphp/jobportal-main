<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_202312011353 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'created_by' => [
                'type' => 'INT',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_screening', $fields);
        $this->db->query('ALTER TABLE tbl_screening ADD CONSTRAINT FOREIGN KEY (created_by) REFERENCES tbl_employers(ID) ON DELETE NO ACTION ON UPDATE NO ACTION'); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_screening', 'created_by');
    }
}
