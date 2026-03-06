<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_seeker_documents_202207141541 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'accounting_approved' => [
                'type' => 'tinyint',
                'null' => false,
                'after' => 'legal_approved'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_seeker_documents', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_seeker_documents', 'accounting_approved');
    }
}
