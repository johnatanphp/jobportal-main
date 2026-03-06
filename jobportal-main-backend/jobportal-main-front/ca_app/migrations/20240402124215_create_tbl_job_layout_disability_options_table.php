<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_disability_options_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'subtheme_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'disability_allow' => [
                'type' => 'TINYINT',
                'null' => false
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (subtheme_id) REFERENCES tbl_job_layout_disability_subthemes(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_job_layout_disability_options');

        $this->db->query('ALTER TABLE `tbl_job_layout_disability_options` ADD UNIQUE INDEX (`job_layout_id`, `subtheme_id`)');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_disability_options');
    }
}
