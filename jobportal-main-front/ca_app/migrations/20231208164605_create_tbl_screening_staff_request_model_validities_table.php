<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_screening_staff_request_model_validities_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'request_model_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'month_validity' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('request_model_id');

        $this->dbforge->create_table('tbl_screening_staff_request_model_validities');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_screening_staff_request_model_validities');
    }

    public function insert_data()
    {
        $this->db->insert('tbl_screening_staff_request_model_validities', [
            'request_model_id' => 1,
            'month_validity' => 3
        ]);

        $this->db->insert('tbl_screening_staff_request_model_validities', [
            'request_model_id' => 2,
            'month_validity' => 6
        ]);

        $this->db->insert('tbl_screening_staff_request_model_validities', [
            'request_model_id' => 3,
            'month_validity' => 6
        ]);
    }
}
