<?php
class Company_account_setting extends CI_Model {

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

	public function save($data_config, $company_id)
	{
		$status = true;

    	foreach ($data_config as $key => $value) {
            if (!is_null($value)) {
                $data = array(
                    'key' => $key,
                    'value' => $value,
                    'company_ID' => $company_id
                );
                $status = $this->db->replace('tbl_company_config', $data);
    	   }
        }
        
    	return $status;
	} 
}
