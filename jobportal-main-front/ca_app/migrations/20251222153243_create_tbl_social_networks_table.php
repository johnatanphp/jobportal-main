<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_social_networks_table extends CI_Migration
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
                'constraint' => '120',
            ],
            'active' => [
                'type' => 'tinyint',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 1
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_social_networks');
        
        $this->insert_data();
    }
    
    public function insert_data()
    {
        $sn_data = [
            ['id' => 1, 'name' => 'Facebook', 'active' => 1],
            ['id' => 2, 'name' => 'Instagram', 'active' => 1],
            ['id' => 3, 'name' => 'TikTok', 'active' => 1],
            ['id' => 4, 'name' => 'LinkedIn', 'active' => 1],
        ];
        
        foreach ($sn_data as $row) {
            
            $sn = $this->db->get_where('tbl_social_networks', [
                'id' => $row['id']
            ])->row();
               
            if ($sn) {
                continue;
            }
            
            $this->db->insert('tbl_social_networks', $row);
        }
    }
    
    public function down()
    {
        $this->dbforge->drop_table('tbl_social_networks');
    }
}
