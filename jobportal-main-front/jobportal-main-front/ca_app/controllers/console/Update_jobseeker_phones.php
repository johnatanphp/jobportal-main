<?php
require_once ("App_console.php");

class Update_jobseeker_phones extends App_console  
{
    public function __construct()
    {
        parent::__construct(); 
    }

    public function run()
    {
        $this->db->select([
            'j.ID AS id',
            'j.mobile AS mobile_phone',
            'j.home_phone AS home_phone'
        ]);
        $this->db->from('tbl_job_seekers j');
        $this->db->where('j.country', 56);
        $this->db->where("(j.mobile not like '+%' OR CHAR_LENGTH(j.mobile) < 12)");
        $this->db->limit(100);

        $results = $this->db->get()->result();

        $update_data = [];
        foreach ($results as $row) {

            $mobile = $this->mobile_phone_number_format($row->mobile);
            
            $update_data[][
                'ID' => $row->id,
                'mobile' => $mobile
            ];
        }

        $this->db->update_batch('tbl_job_seekers', $update_data, 'ID');
    }

    function mobile_phone_number_format($mobile)
    {       
        $mobile = trim((string)$mobile);
        $mobile = str_replace(' ', '', $mobile);
        $mobile = str_replace('-', '', $mobile);
        $mobile = str_replace('+', '', $mobile);

        if (strlen($mobile) == 9 && $mobile[0] == '9') {
            $mobile = '51' . $mobile;
        }

        $mobile = '+' . $mobile;

         try {
            $phone_number_util = \libphonenumber\PhoneNumberUtil::getInstance();

            $phone_number_object = $phone_number_util->parse($mobile, null);

            $is_valid_number = $phone_number_util->isValidNumber($phone_number_object);

            if ($is_valid_number == true) {
                return $mobile;
            }
        } catch (\libphonenumber\NumberParseException $e) {}

        return '';
    }
}
