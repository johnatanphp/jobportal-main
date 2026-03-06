<?php
class Jobseeker_account_setting extends CI_Model {

    private $account_setting;

	public function __construct()
	{
		$this->account_setting = array();
	}

	public function set_item($key, $value)
	{
		$this->account_setting[$key] = $value;
	}

	public function item($key)
	{
		return isset($this->account_setting[$key]) ? $this->account_setting[$key] : null; 
	}

	public function save($data_config, $seeker_id)
	{
		$status = true;

    	foreach ($data_config as $key => $value) {
            if (!is_null($value)) {
                $data = array(
                    'key' => $key,
                    'value' => $value,
                    'seeker_ID' => $seeker_id
                );
                
                $status = $this->db->replace(
                	'tbl_seeker_config', 
                	$data
                );
    	   }
        }
        
    	return $status;
	} 
}
