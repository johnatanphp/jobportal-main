<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_tray_candidates_202510271450 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'sent_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_tray_candidates', $fields); 
        $this->db->query('ALTER TABLE tbl_recruitment_tray_candidates ADD CONSTRAINT FOREIGN KEY (sent_id) REFERENCES tbl_recruitment_tray_sent(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_tray_candidates', 'sent_id');
    }
}
