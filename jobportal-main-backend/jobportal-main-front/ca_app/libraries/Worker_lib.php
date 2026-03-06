<?php

class Worker_lib
{
	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function process()
    {
        while (true) {
            $job = $this->queue_lib->pop();

            if ($job) {
                try {
                    $this->queue_lib->process($job);
                } catch (Exception $e) {}
            }
            
            sleep(3); // Evitar sobrecarga de CPU
        }
    }
}
