<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_seeker_permission_signs_contracts_table extends CI_Migration
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
                'unsigned' => TRUE
            ],
            'file_path' => [
                'type' => 'TEXT'
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->create_table('tbl_seeker_permission_signs_contracts');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_seeker_permission_signs_contracts');
    }
}
