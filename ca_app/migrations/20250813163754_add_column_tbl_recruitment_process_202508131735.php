<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_process_202508131735 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'request_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'country_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'department_id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'default' => ''
            ],
            'province_id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'default' => ''
            ],
            'district_id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => false,
                'default' => ''
            ],
            'commercial_premise_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'vacancies' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_process', $fields); 
        $this->db->query("CREATE INDEX request_id ON tbl_recruitment_process (request_id)");
        $this->db->query("CREATE INDEX commercial_premise_id ON tbl_recruitment_process (commercial_premise_id)");
        $this->db->query("CREATE INDEX country_id ON tbl_recruitment_process (country_id)");
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_process', 'request_id');
        $this->dbforge->drop_column('tbl_recruitment_process', 'country_id');
        $this->dbforge->drop_column('tbl_recruitment_process', 'department_id');
        $this->dbforge->drop_column('tbl_recruitment_process', 'province_id');
        $this->dbforge->drop_column('tbl_recruitment_process', 'district_id');
        $this->dbforge->drop_column('tbl_recruitment_process', 'commercial_premise_id');
        $this->dbforge->drop_column('tbl_recruitment_process', 'vacancies');
    }
}
