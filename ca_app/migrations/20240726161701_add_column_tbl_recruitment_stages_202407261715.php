<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_stages_202407261715 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'description' => [
                'type' => 'TEXT',
                'after' => 'name',
                'null' => false
            ],
            'stage_category_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
            ],
            'stage_group_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
            ],  
        ];

        $this->dbforge->add_column('tbl_recruitment_stages', $fields);   
        $this->db->query('ALTER TABLE tbl_recruitment_stages ADD CONSTRAINT FOREIGN KEY (stage_category_id) REFERENCES tbl_recruitment_stages_categories(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_recruitment_stages ADD CONSTRAINT FOREIGN KEY (stage_group_id) REFERENCES tbl_recruitment_stages_groups(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_stages', 'description');
        $this->dbforge->drop_colum('tbl_recruitment_stages', 'stage_category_id');
        $this->dbforge->drop_colum('tbl_recruitment_stages', 'stage_group_id');
    }
}
