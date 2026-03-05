<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_staff_request_sts_process_types_202407261150 extends CI_Migration
{
    public function up()
    {
        $list_sts = [
            'pending' => 'Pendiente',
            'unassigned' => 'Sin asignar',
            'assigned' => 'Asignada',
            'published' => 'Publicada',
            'suspended' => 'Suspendida',
            'rejected' => 'Rechazada',
            'canceled' => 'Cancelada',
            '8' => 'No iniciado',
            '9' => 'Iniciado'
        ];

        foreach ($list_sts as $id => $sts_name) {
            $this->db->insert('tbl_staff_request_sts_process_types', [
                'id' => $id,
                'name' => $sts_name,
                'active' => 1
            ]);
        }
    }

    public function down()
    {
        $this->db->truncate('tbl_staff_request_sts_process_types');
    }
}
