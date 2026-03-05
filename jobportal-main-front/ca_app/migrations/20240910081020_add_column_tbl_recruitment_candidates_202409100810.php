<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_candidates_202409100810 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'rejected_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'rejected_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'rejected_by_user' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true,
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_candidates', $fields);

        $this->db->query('ALTER TABLE tbl_recruitment_candidates ADD CONSTRAINT FOREIGN KEY (rejected_by_user) REFERENCES tbl_employers(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_candidates', 'rejected_date');
        $this->dbforge->drop_column('tbl_recruitment_candidates', 'rejected_time');
        $this->dbforge->drop_column('tbl_recruitment_candidates', 'rejected_by_user');
    }
}
