<?php
class MY_Email extends CI_Email 
{
	public function __construct($config = array())
	{
		parent::__construct($config);	
	}
	
	public function init()
	{
		$ci =& get_instance();

		$config = $ci->Email_drafts->email_configuration();
        $ci->email->initialize($config);
        $ci->email->clear(TRUE);
        $ci->email->from(ADMIN_EMAIL, SITE_NAME);
	}

	public function to($to)
	{
		$CI =& get_instance();

        if ($CI->config->item('env') == 'development') {
            
            parent::to(explode(',', $CI->config->item('email_notifications')));    
            
            return;
        }
		
		parent::to($to);
	}

	public function cc($cc)
	{
		$CI =& get_instance();

        if ($CI->config->item('env') == 'development') {
            
            parent::cc(explode(',', $CI->config->item('email_notifications')));    
            
            return;
        }
		
		parent::cc($cc);
	}

	public function bcc($bcc, $limit = '')
	{
		$CI =& get_instance();

        if ($CI->config->item('env') == 'development') {
            
            parent::bcc(explode(',', $CI->config->item('email_notifications')), $limit);    
            
            return;
        }
		
		parent::bcc($bcc, $limit);
	}
}
