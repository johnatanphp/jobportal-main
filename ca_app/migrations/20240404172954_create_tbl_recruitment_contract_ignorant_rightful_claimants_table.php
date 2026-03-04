<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_contract_ignorant_rightful_claimants_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'seeker_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => false
            ]
        ]);
        $this->dbforge->add_key('seeker_id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_contract_ignorant_rightful_claimants');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_contract_ignorant_rightful_claimants');
    }
}
