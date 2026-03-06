<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_202407111558 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'attached_document_license' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
                'null' => true,
                'after' => 'form_rtps_file_path',
            ],
        ];

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'attached_document_license');
    }
}
