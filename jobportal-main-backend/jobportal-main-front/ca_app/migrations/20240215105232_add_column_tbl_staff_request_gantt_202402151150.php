<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_request_gantt_202402151150 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_staff_request_gantt DROP INDEX request_ID");

        $fields = [
            'type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'ignore_weekend'
            ]
        ];

        $this->dbforge->add_column('tbl_staff_request_gantt', $fields); 
        $this->db->query('ALTER TABLE `tbl_staff_request_gantt` ADD INDEX request_ID (`request_ID`)');
        $this->db->query('ALTER TABLE tbl_staff_request_gantt ADD CONSTRAINT FOREIGN KEY (type_id) REFERENCES tbl_gantt_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_request_gantt', 'type_id');
    }
}
