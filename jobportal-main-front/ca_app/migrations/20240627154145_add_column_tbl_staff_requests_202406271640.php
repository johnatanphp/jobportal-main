<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202406271640 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'priority_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'campaign_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'job_duration' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false,
                'default' => ''
            ],
            'job_location_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'job_worker_education_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'job_worker_experience_months' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'job_worker_experience_channel' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'job_worker_experience_client' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'job_worker_national' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'job_worker_health_carnet' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'job_worker_health_insurance' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'job_worker_health_disabilities' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'job_description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'job_list_type' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'priority_id');
        $this->dbforge->drop_colum('tbl_staff_requests', 'campaign_id');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_duration');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_location_type_id');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_education_id');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_experience_months');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_experience_channel');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_experience_client');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_national');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_health_carnet');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_health_insurance');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_worker_health_disabilities');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_description');
        $this->dbforge->drop_colum('tbl_staff_requests', 'job_list_type');
    }
}
