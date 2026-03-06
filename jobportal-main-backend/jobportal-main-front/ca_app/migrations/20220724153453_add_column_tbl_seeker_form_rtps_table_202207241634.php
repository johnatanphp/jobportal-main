<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_table_202207241634 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'paternal_last_name' => [
                'type' => 'varchar',
                'constraint' => '30',
                'null' => TRUE,
                'after' => 'first_name'
            ],
            'maternal_last_name' => [
                'type' => 'varchar',
                'constraint' => '30',
                'null' => TRUE,
                'after' => 'paternal_last_name'
            ],
            'job_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'seeker_ID'
            ],
            'disability_type' => [
                'type' => 'varchar',
                'constraint' => '30',
                'null' => TRUE,
            ],
            'driver_license' => [
                'type' => 'tinyint',
                'null' => TRUE
            ],
            'driver_license_type' => [
                'type' => 'varchar',
                'constraint' => '50',
                'null' => TRUE
            ],
            'unionized' => [
                'type' => 'tinyint',
                'null' => TRUE
            ],
            'fifth_category_income' => [
                'type' => 'tinyint',
                'null' => TRUE
            ],
            'form_rtps_file_path' => [
                'type' => 'text',
                'null' => TRUE
            ],
            'evicertia_status' => [
                'type' => 'INT',
                'null' => TRUE
            ],
            'evicertia_url_push_notification' => [
                'type' => 'text',
                'null' => TRUE
            ],
            'evicertia_unique_id' => [
                'type' => 'text',
                'null' => TRUE
            ],
            'evicertia_error' => [
                'type' => 'text',
                'null' => TRUE
            ],
            'evicertia_data_log' => [
                'type' => 'text',
                'null' => TRUE
            ],
            'evicertia_send_date' => [
                'type' => 'datetime',
                'null' => TRUE
            ]
        ];
        
        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('evicertia_unique_id');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'paternal_last_name');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'maternal_last_name');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'job_id');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'fifth_category_income');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'form_rtps_file_path');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'evicertia_status');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'evicertia_url_push_notification');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'evicertia_unique_id');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'evicertia_error');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'evicertia_data_log');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'evicertia_send_date');        
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'disability_type');        
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'driver_license');        
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'driver_license_type');        
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'unionized');
    }
}
