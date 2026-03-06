<?php
class Send_email_job
{
    private $params;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function handle()
    {
        $seeker = $this->Job_seeker->find($this->params['seeker_id']);

        $config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to('jesuscanache2017@gmail.com');
		$this->email->subject('Saludo desde Queue Codeigiter');
		$this->email->message('Hola Sr. ' . $seeker->first_name . ' - ' . $this->params['date']);
        $this->email->send();
    }
}
