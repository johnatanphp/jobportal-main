<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_tray_status_202409241515 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'bg_alert_color' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false,
                'after' => 'name'
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_tray_status', $fields); 

        $this->update_bg_alert_color();
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_tray_status', 'bg_alert_color');
    }

    private function update_bg_alert_color()
    {
        $status = [
            ['id' => 1, 'bg_alert_color' => '#777777'],
            ['id' => 2, 'bg_alert_color' => '#0D6EFD'],
            ['id' => 3, 'bg_alert_color' => '#43a966'],
            ['id' => 4, 'bg_alert_color' => '#9b8523'],
            ['id' => 5, 'bg_alert_color' => '#d76363'],
        ];

        foreach ($status as $row) {
            $this->db->where('id', $row['id']);
            $this->db->update('tbl_recruitment_tray_status', [
                'bg_alert_color' => $row['bg_alert_color']
            ]);
        }
    }
}
