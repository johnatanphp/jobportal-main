<?php
require_once ("App_console.php");

class Update_seeker_entries_filters extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        //Migrar canal de reclutamiento
        $results = $this->db->select([
                'DISTINCT(recruitment_channel) AS channel'
            ])
            ->from('tbl_seeker_entries')
            ->where('for_job_ID', 0)
            ->get()
            ->result();

        foreach ($results as $row) {
            $this->db->select('id');
            $this->db->from('tbl_seeker_entries_channels');
            $this->db->where('name', $row->channel);
            $count = $this->db->count_all_results();

            if ($count == 0) {
                $this->db->insert('tbl_seeker_entries_channels', [
                    'name' => $row->channel
                ]);
            }
        }

        //Migrar puestos
        $results = $this->db->select([
            'DISTINCT(job_title) AS job_title'
            ])
            ->from('tbl_seeker_entries')
            ->where('for_job_ID', 0)
            ->get()
            ->result();

        foreach ($results as $row) {
            $this->db->select('id');
            $this->db->from('tbl_seeker_entries_jobs');
            $this->db->where('name', $row->job_title);
            $count = $this->db->count_all_results();

            if ($count == 0) {
                $this->db->insert('tbl_seeker_entries_jobs', [
                    'name' => $row->job_title
                ]);
            }
        }

        //Migrar clientes
        $results = $this->db->select([
            'DISTINCT(company_account) AS clients'
            ])
            ->from('tbl_seeker_entries')
            ->where('for_job_ID', 0)
            ->get()
            ->result();

        foreach ($results as $row) {
            $this->db->select('id');
            $this->db->from('tbl_seeker_entries_clients');
            $this->db->where('name', $row->clients);
            $count = $this->db->count_all_results();

            if ($count == 0) {
                $this->db->insert('tbl_seeker_entries_clients', [
                    'name' => $row->clients
                ]);
            }
        }
    }
}
