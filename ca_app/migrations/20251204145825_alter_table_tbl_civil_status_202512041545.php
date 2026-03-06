<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_civil_status_202512041545 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'hrmgo_code' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true,
                'after' => 'name'
            ]
        ];

        $this->dbforge->add_column('tbl_civil_status', $fields);

        $this->update_data();
    }

    public function update_data()
    {   
        $data = [
            '1'	=> '02', // Soltero	
            '2'	=> '01', // Casado	
            '4'	=> '03'  // Conviviente	
        ];  

        foreach ($data as $id => $hrmgo_code) {

            $this->db->where('id', $id);
            $this->db->update('tbl_civil_status', [
                'hrmgo_code' => $hrmgo_code
            ]);
        }
    }

    public function down(){}
}
