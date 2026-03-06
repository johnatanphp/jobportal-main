<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_email_applicant_phase_202602161750 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'channel' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_email_applicant_phase', $fields);
        
        $this->update_data();
    }
    
    private function update_data()
    {
        $this->db->where("1=1");
        $this->db->update('tbl_email_applicant_phase', [
            'channel' => 'email'
        ]);
        
        $this->db->where("id", 12);
        $this->db->update('tbl_email_applicant_phase', [
            'channel' => 'whatsapp'
        ]);
    }
    
    public function down(){}
}
