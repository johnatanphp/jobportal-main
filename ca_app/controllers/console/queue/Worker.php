<?php
class Worker extends CI_Controller
{
	public function process()
	{
		$this->load->library('Worker_lib');
		try {
			$this->worker_lib->process();
		} catch (Exception $e) {}
	}
	
	public function pop()
	{
	    $this->load->library('Queue_lib');
        $job = $this->queue_lib->pop();

        if ($job) {
            try {
                $this->queue_lib->process($job);
            } catch (Exception $e) {}
        }
	}
}
