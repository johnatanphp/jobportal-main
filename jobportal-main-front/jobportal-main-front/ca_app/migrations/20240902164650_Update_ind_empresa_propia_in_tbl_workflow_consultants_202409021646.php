<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_ind_empresa_propia_in_tbl_workflow_consultants_202409021646 extends CI_Migration
{
    public function up()
    {
        $consultoras = [
            'OVERALL BUSINESS S.A.',
            'OVERALL STRATEGY S.A.C.',
            'EXECUTIVE SOLUTIONS S.A.',
            'BUSINESS CONSULTANTS S.A.',
            'OVERALL SPORTS S.A.',
            'MARKETING POWER S.A.C.',
            'TRADE DEVELOPMENT S.A.C.',
            'SMARTPOINT SAC',
            'PEOPLE S.A.',
            'OVERALL ORIENTE S.A.C.',
            'INDUSTRY & LOGISTICS MANAGEMENT S.A.'
        ];

        $this->db->where_in('name', $consultoras);
        $this->db->update('tbl_workflow_consultants', ['ind_own_company' => TRUE]);
    }

    public function down()
    {
        $consultoras = [
            'OVERALL BUSINESS S.A.',
            'OVERALL STRATEGY S.A.C.',
            'EXECUTIVE SOLUTIONS S.A.',
            'BUSINESS CONSULTANTS S.A.',
            'OVERALL SPORTS S.A.',
            'MARKETING POWER S.A.C.',
            'TRADE DEVELOPMENT S.A.C.',
            'SMARTPOINT SAC',
            'PEOPLE S.A.',
            'OVERALL ORIENTE S.A.C.',
            'INDUSTRY & LOGISTICS MANAGEMENT S.A.'
        ];

        $this->db->where_in('name', $consultoras);
        $this->db->update('tbl_workflow_consultants', ['ind_own_company' => FALSE]);
    }
}
