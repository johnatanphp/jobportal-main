<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cron_schedule_lib {

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function run($cron_name, $time)
    {
        $cron = new Cron\CronExpression($time);
   
        if ($cron->isDue()) {
            system("php " . FCPATH . "index.php console/$cron_name run");
        }
    }
}
