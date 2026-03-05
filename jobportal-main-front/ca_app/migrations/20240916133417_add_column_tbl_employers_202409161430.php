<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_employers_202409161430 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'rrhh_type_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'type'
            ],
        ];

        $this->dbforge->add_column('tbl_employers', $fields); 

        $this->db->query('ALTER TABLE tbl_employers ADD CONSTRAINT FOREIGN KEY (rrhh_type_id) REFERENCES tbl_employer_rrhh_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_employers', 'rrhh_type_id');
    }
}
