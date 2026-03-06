<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_post_jobs_202410021855 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'job_ignore' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
            ]
        ];

        $this->dbforge->add_column('tbl_post_jobs', $fields);
        $this->db->query("CREATE INDEX job_ignore ON tbl_post_jobs (job_ignore)");
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_post_jobs', 'job_ignore');
    }
}
