<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_skills_202410021315 extends CI_Migration
{
    public function up()
    {
        if (!$this->db->field_exists('occupational_category_id', 'tbl_skills')) {
            $fields = [
                'occupational_category_id' => [
                    'type' => 'INT',
                    'unsigned' => TRUE,
                    'null' => TRUE
                ],
            ];
            $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (occupational_category_id) REFERENCES tbl_occupational_categories(id)');
            $this->dbforge->add_column('tbl_skills', $fields);
        }
    }
    
    public function down()
    {
        if ($this->db->field_exists('occupational_category_id', 'tbl_skills')) {
            $this->dbforge->drop_column('tbl_skills', 'occupational_category_id');
        }
    }
}
