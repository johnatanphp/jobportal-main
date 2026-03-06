<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_short_list_tokens_202601181824 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'process_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_short_list_tokens', $fields);
        $this->db->query('ALTER TABLE tbl_recruitment_short_list_tokens ADD CONSTRAINT FOREIGN KEY (process_id) REFERENCES tbl_recruitment_process(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    
        $fields = [
            'job_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true,
            ],
        ];
        
        $this->dbforge->modify_column('tbl_recruitment_short_list_tokens', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_short_list_tokens', 'process_id');
    }
}
