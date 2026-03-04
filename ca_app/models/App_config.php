<?php

class App_config extends CI_Model
{ 
    public function save($data_config)
    {
    	$result = true;

    	foreach ($data_config as $key => $value) {
            if (!is_null($value)) {
                $data = array(
                    'key' => $key,
                    'value' => $value
                );

                $result = $this->db->replace('tbl_app_config', $data);
    	   }
        }
        
    	return $result;
    }
}
