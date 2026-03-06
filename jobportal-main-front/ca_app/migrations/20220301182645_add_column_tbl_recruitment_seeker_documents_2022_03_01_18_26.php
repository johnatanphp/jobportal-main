<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_seeker_documents_2022_03_01_18_26 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'legal_approved' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0,
                'after' => 'approved'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_seeker_documents', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_seeker_documents', 'legal_approved');
    }
}
