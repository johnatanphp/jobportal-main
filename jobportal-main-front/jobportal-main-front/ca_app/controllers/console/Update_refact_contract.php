<?php
require_once ("App_console.php");

class Update_refact_contract extends App_console  
{
    private $limit;

    public function __construct()
    {
        parent::__construct();  
        $this->limit = 50;
    }

    public function run()
    {
        $this->update_recruitment_contracts();
        $this->update_recruitment_contracts_logs();
    }

    private function update_recruitment_contracts()
    {
        $this->db->select([
            'rc.id AS rc_id',
            'p.id AS process_id'
        ]);
        $this->db->from('tbl_recruitment_contracts rc');
        $this->db->join('tbl_recruitment_process p', 'rc.job_id=p.job_ID');
        $this->db->where('rc.process_id IS NULL');
        $this->db->limit($this->limit);
        $results = $this->db->get()->result();

        foreach ($results as $row) {

            $this->db->where('id', $row->rc_id);
            $this->db->update('tbl_recruitment_contracts', [
                'process_id' => $row->process_id
            ]);
        }
    }

    private function update_recruitment_contracts_logs()
    {
        $this->db->select([
            'rcl.id AS rcl_id',
            'p.id AS process_id'
        ]);
        $this->db->from('tbl_recruitment_contracts_synchronization_logs rcl');
        $this->db->join('tbl_recruitment_process p', 'rcl.job_id=p.job_ID');
        $this->db->where('rcl.process_id IS NULL');
        $this->db->limit($this->limit);
        $results = $this->db->get()->result();

        foreach ($results as $row) {

            $this->db->where('id', $row->rcl_id);
            $this->db->update('tbl_recruitment_contracts_synchronization_logs', [
                'process_id' => $row->process_id
            ]);
        }
    }
}
