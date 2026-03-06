<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_employer_responsibles_clients_permissions_202503031920 extends CI_Migration
{
    public function up()
    {
        if ($this->config->item('env') !== 'production') {
            $this->insert_data();
        }
    }

    private function insert_data()
    {
        $this->db->from('tbl_employers');
        $this->db->where_in('ID', [1, 14]);
        $this->db->where('company_ID', 1);
        $employers = $this->db->get()->result();

        $this->db->select([
            'consultants.code AS consultant_code',
            'clients.code AS client_code'
        ]);
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=cost_centers.cia_code');
        $this->db->join('tbl_workflow_clients clients', 'clients.code=cost_centers.client_code AND cost_centers.cia_code=consultants.code');
        $this->db->where('cost_centers.company_id', 1);
        $this->db->where('cost_centers.active', 1);
        $this->db->where_in('consultants.code', ['01', '02']);
        $this->db->group_by(['consultants.code', 'clients.code']);

        $clients = $this->db->get()->result();

        //dd($clients);
        
        foreach ($employers as $emp_row) {

            foreach ($clients as $client_row) {
                $permission = $this->db->get_where('tbl_employer_responsibles_clients_permissions', [
                    'employer_id' => $emp_row->ID,
                    'company_id' => $emp_row->company_ID,
                    'cia_code' => $client_row->consultant_code,
                    'client_code' => $client_row->client_code
                ])->row();
    
                if (!$permission) {
                    $this->db->insert('tbl_employer_responsibles_clients_permissions', [
                        'employer_id' => $emp_row->ID,
                        'company_id' => $emp_row->company_ID,
                        'cia_code' => $client_row->consultant_code,
                        'client_code' => $client_row->client_code
                    ]);
                }
            }
        }
    }

    public function down(){}
}
