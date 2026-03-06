<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_exam_request_seeker_exams_202309041045_table extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_exam_request_seeker_exams DROP COLUMN realized, DROP COLUMN price, DROP COLUMN oc_created,  DROP COLUMN oc_created_date, DROP COLUMN oc_id, DROP COLUMN oc_error, DROP COLUMN oc_data_log");

        $fields = [
            'schedule_id' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
            ],
            'request_id' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'scheduled_date' => [
                'type' => 'DATE',
                'null' => true
            ],
            'file_high_path' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'file_oc_path' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'medical_center_code' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '150',
            ],
            'medical_center_name' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'medical_center_location_id' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
            ],
            'medical_center_location_name' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '80'
            ],
            'exam_date' => [
                'type' => 'DATE',
                'null' => true
            ],
            'exam_time' => [
                'type' => 'TIME',
                'null' => true
            ],
            'ubigeo' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'notification_type' => [
                'type' => 'TINYINT',
                'null' => false
            ],
            'candidate_notified' => [
                'type' => 'TINYINT',
                'null' => false
            ],
            'notify_candidate' => [
                'type' => 'TINYINT',
                'null' => false
            ],
            'notified_sso' => [
                'type' => 'TINYINT',
                'null' => false,
            ],
            'status' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
            ],	        
            'active' => [
                'type' => 'TINYINT',
                'null' => false,
            ]   
        ];
      
        $this->dbforge->add_column('tbl_exam_request_seeker_exams', $fields); 
        
        $this->db->query("CREATE INDEX active ON tbl_exam_request_seeker_exams (active)");  
        $this->db->query("CREATE INDEX `status` ON tbl_exam_request_seeker_exams (`status`)");
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'schedule_id');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'request_id');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'created_at');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'scheduled_date');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'file_high_path');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'file_oc_path');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'medical_center_code');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'medical_center_name');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'medical_center_location_id');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'medical_center_location_name');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'exam_date');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'exam_time');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'ubigeo');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'comment');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'notification_type');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'candidate_notified');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'notify_candidate');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'notified_sso');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'status');
        $this->dbforge->drop_colum('tbl_exam_request_seeker_exams', 'active');   
    }
}
