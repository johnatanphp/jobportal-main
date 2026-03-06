<?php
require_once ("App_console.php");

class Migration_hrmgo_workflow_overall extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'company.ID AS company_id'
        ]);
        $this->db->from('tbl_companies company');
        $this->db->join('tbl_countries countries', 'countries.ID=company.country_id');
        $this->db->where('company.system_internal', 1);
        $this->db->where('countries.has_operation_overall', 1);
        $this->db->where_in('countries.iso_3166_1_alpha2', ['MX', 'EC']);
        $results = $this->db->get()->result();

        $company_ids = [];

        foreach ($results as $row) {
            $company_ids[] = $row->company_id;
        }

        $this->load->library(
            'WS_hrmgo/WS_hrmgo_workflow_overall_lib'
        );
        $this->ws_hrmgo_workflow_overall_lib->migrate($company_ids);        
    }
}
