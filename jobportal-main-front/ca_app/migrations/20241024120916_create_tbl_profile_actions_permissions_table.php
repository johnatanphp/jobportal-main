<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_profile_actions_permissions_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'profile_id' => [
                'type' => 'INT',
                'unsigned' => false,
            ],
            'action_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'employer_id' => [
                'type' => 'INT',
                'unsigned' => false,
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (profile_id) REFERENCES tbl_profiles(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (action_id) REFERENCES tbl_modules_actions(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_key(['profile_id', 'action_id', 'employer_id'], TRUE);
        $this->dbforge->create_table('tbl_profile_actions_permissions');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_profile_actions_permissions');
    }
}
