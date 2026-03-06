<?php
require_once ("App_console.php");

class Update_password_seekers extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->from('tbl_job_seekers');
        $this->db->where("SUBSTRING(password, 1, 7) != '$2y$10$'");

        $this->db->limit(500);

        $r = $this->db->get()->result();

        foreach ($r as $key => $row) {
            
            if (empty($row->password)) {
                continue;
            }

            if (substr($row->password, 0, 7) != '$2y$10$') {

                $data = [
                    'password' => do_hashing($row->password)
                ];

                //echo $row->password . '<br />';
                $this->db->where('ID', $row->ID);
                $this->db->update('tbl_job_seekers', $data);
            }
        }   
    }
}
