<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_period_rules_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'start' => [
                'type' => 'FLOAT',
                'null' => false
            ],
            'end' => [
                'type' => 'FLOAT',
                'null' => true
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_period_rules');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_period_rules');
    }

    private function insert_data()
    {
        $this->db->insert('tbl_recruitment_period_rules', [
            'start' => '0',
            'end' => '2.99',
            'color' => '#3ec868'
        ]);
        $this->db->insert('tbl_recruitment_period_rules', [
            'start' => '3',
            'end' => '3.99',
            'color' => '#f48306'
        ]);
        $this->db->insert('tbl_recruitment_period_rules', [
            'start' => '4',
            'end' => null,
            'color' => '#d7001a'
        ]);
    }
}
