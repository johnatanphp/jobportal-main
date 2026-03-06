<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_exam_request_seeker_exams_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'exam_request_seeker_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'exam_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'exam_doc_type' => [
                'type' => 'VARCHAR',
                'constraint' => 180
            ],
            'protocol_extra' => [
                'type' => 'TEXT'
            ],
            'type_expense' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'realized' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'oc_created' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'oc_created_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'oc_id' => [
                'type' => 'VARCHAR',
                'constraint' => 180
            ],
            'oc_error' => [
                'type' => 'TEXT'
            ],
            'oc_data_log' => [
                'type' => 'TEXT'
            ]
        ];
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (exam_request_seeker_id) REFERENCES tbl_exam_request_seekers(id) ON DELETE CASCADE');
        $this->dbforge->add_key(['exam_request_seeker_id', 'exam_type_id'], TRUE);
        $this->dbforge->create_table('tbl_exam_request_seeker_exams');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_exam_request_seeker_exams', TRUE);
    }
}
