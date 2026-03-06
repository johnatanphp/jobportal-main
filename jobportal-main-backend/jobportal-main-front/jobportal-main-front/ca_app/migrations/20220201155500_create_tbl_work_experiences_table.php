<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_work_experiences_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 120
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
        ];
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_work_experiences');

        //Insert data
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_work_experiences', TRUE);
    }

    private function insert_data()
    {
        $insert[] = ['fresh', 'Sin experiencia'];
        $insert[] = ['3m', '3 meses'];
        $insert[] = ['6m', '6 meses'];
        $insert[] = ['1', '1 año'];
        $insert[] = ['2', '2 años'];
        $insert[] = ['3', '3 años'];
        $insert[] = ['4', '4 años'];
        $insert[] = ['5', '5 años'];
        $insert[] = ['6', '6 años'];
        $insert[] = ['7', '7 años'];
        $insert[] = ['8', '8 años'];
        $insert[] = ['9', '9 años'];
        $insert[] = ['10', '10 años'];
        $insert[] = ['10+', 'Más de 10 años'];
        $insert[] = ['15', '0 HASTA 6 MESES'];
        $insert[] = ['16', 'MAYOR DE 6 A 12 MESES'];
        $insert[] = ['17', 'MAYOR DE 1 HASTA 2 AÑOS'];
        $insert[] = ['18', 'MAYOR DE  2 HASTA 4 AÑOS'];
        $insert[] = ['19', 'MAYOR DE  5 AÑOS'];
        
        foreach ($insert as $row) {

            $data_row = [
                'code' => $row[0],
                'name' => $row[1],
                'active' => 1
            ];
            $this->db->insert('tbl_work_experiences', $data_row);
        }
    }
}
