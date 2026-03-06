<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_tray_candidates_202409101020 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'link_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'link_sent' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_tray_candidates', $fields); 

        $this->db->query("CREATE INDEX link_sent ON tbl_recruitment_tray_candidates (link_sent)");
        $this->db->query('ALTER TABLE tbl_recruitment_tray_candidates ADD CONSTRAINT FOREIGN KEY (link_id) REFERENCES tbl_recruitment_document_requests(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_tray_candidates', 'link_id');
        $this->dbforge->drop_colum('tbl_recruitment_tray_candidates', 'link_sent');
    }
}
