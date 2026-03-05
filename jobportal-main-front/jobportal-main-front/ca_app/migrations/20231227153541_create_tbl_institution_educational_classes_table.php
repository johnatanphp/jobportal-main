<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_institution_educational_classes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
            ],
            'active' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_institution_educational_classes');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_institution_educational_classes');
    }

    public function insert_data()
    {
        $records = [
            ['1', 'COLEGIO'],
            ['2', 'INSTITUTO'],
            ['3', 'UNIVERSIDAD']
        ];

        foreach ($records as $row ) {
            $this->db->insert('tbl_institution_educational_classes', [
                'id' => $row[0],
                'name' => $row[1]
            ]);
        }
    }
}
