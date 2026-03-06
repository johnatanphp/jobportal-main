<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aws_sns_lib {
    
    private $aws_client = null;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->aws_client = new Aws\Sns\SnsClient([
            'region'  => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => $this->config->item('aws_sns_key'), 
                'secret' => $this->config->item('aws_sns_secret')
            ]
        ]);
    }

    private function format_phone_number($phone_number)
    {
        $part_phone_number  = explode(' ', $phone_number);

        if (count($part_phone_number) == 2) {
            $code = $part_phone_number[0];
            $number = $part_phone_number[1];
        }

        if (count($part_phone_number) == 3) {
            $code = $part_phone_number[0] . ' ' . $part_phone_number[1];
            $number = $part_phone_number[2];
        }

        $number = ltrim($number, '0');

        return $code . $number;
    }

    public function send_text_message($sms, $phone_number)
    {
        return $this->aws_client->publish([

            'Message' => $sms,
            'PhoneNumber' => $this->format_phone_number($phone_number),    
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType'  => [
                    'DataType'    => 'String',
                    'StringValue' => 'Transactional',
                 ],
             ],
          ]);
    }
}
