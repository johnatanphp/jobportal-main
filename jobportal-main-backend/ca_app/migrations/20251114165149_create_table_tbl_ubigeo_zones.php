<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_table_tbl_ubigeo_zones extends CI_Migration
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
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_ubigeo_zones');

        $this->insert_data();
    }

    public function insert_data()
    {
        $this->db->select([
            'ubigeos.code_zone',
            'ubigeos.description_zone'
        ]);
        $this->db->from('tbl_ubigeos ubigeos');
        $this->db->where('ubigeos.code_zone IS NOT NULL');
        $this->db->group_by('ubigeos.code_zone');
        $results = $this->db->get()->result();

        foreach ($results as $row) {

            $this->db->insert('tbl_ubigeo_zones', [
                'id' => $row->code_zone,
                'name' => $row->description_zone,
                'active' => 1
            ]);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_ubigeo_zones');
    }
}
