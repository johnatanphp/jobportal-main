<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_entries_202501311625 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'notify_by_mail' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
            ],
            'notify_by_whatsapp' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_entries', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_seeker_entries', 'notify_by_mail');
        $this->dbforge->drop_column('tbl_seeker_entries', 'notify_by_whatsapp');
    }
}
