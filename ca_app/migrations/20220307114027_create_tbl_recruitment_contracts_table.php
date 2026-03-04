<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_contracts_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'seeker_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'job_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'identification_doc_type' => [
                'type' => 'INT',
                'null' => false
            ],
            'identification_doc_number' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
            'is_peruvian' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'cod_trab' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'hired_by_user_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'no_cia' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true
            ],
            'date_admission' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'period_year' => [
                'type' => 'SMALLINT',
                'unsigned' => TRUE,
                'null' => TRUE
            ],
            'period_month' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => TRUE
            ],
            'total_salary' => [
                'type' => 'DOUBLE',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'contract_start_date' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'contract_end_date' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'hired_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'data_log' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_contracts');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_contracts', true);
    }
}
