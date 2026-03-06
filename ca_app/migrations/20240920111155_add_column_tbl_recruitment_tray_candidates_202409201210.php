<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_tray_candidates_202409201210 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'company_id' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'client_code'
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_tray_candidates', $fields); 

        $this->db->query('ALTER TABLE tbl_recruitment_tray_candidates ADD CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_tray_candidates', 'company_id');
    }
}
