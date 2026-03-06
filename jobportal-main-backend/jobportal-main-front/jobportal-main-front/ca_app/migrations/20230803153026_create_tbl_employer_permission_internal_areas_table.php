<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employer_permission_internal_areas_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'area_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'NULL' => false
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'NULL' => false
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (area_id) REFERENCES tbl_internal_areas(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (user_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->create_table('tbl_employer_permission_internal_areas');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employer_permission_internal_areas');
    }
}
