<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_identity_document_types_202512041545 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'hrmgo_code' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true,
                'after' => 'sunat_code'
            ]
        ];

        $this->dbforge->add_column('tbl_identity_document_types', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_identity_document_types', 'hrmgo_code');
    }      
}
