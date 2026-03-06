<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_gantt_types_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_gantt_types');
        
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_gantt_types');
    }

    private function insert_data()
    {    
        $types = [
            1 => 'ACTIVIDADES',
            2 => 'ENTREVISTAS', 
        ];
        
        foreach ($types as $id => $name) {
            $this->db->insert('tbl_gantt_types', [
                'id' => $id,
                'name' => $name
            ]);
        }
    }
}
