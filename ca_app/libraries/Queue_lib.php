<?php

class Queue_lib
{
	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function push($job, $data = [])
    {
        $payload = json_encode([
            'job' => $job,
            'data' => $data
        ]);

        $this->db->insert('jobs', [
            'queue' => 'default',
            'payload' => $payload,
            'attempts' => 0,
            'available_at' => time(),
            'created_at' => time()
        ]);

        $job_id = $this->db->insert_id();

        return $job_id;
    }

    public function pop()
    {
        $this->db->from('jobs');
        $this->db->where('status', 0);
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);
        $job = $this->db->get()->row();

        if (!$job) {
            return null;
        }
   
        return $job;
    }

    public function process($job)
    {
        $this->db->where('id', $job->id);
        $this->db->update('jobs', [
            'status' => 1
        ]);

        $payload = json_decode($job->payload);
        $job_name = $payload->job;
        $class_name = strtolower($job_name) . '_' . bin2hex(random_bytes(8));

        try {
            $this->load->library('Jobs/' . $job_name, (array)$payload->data, $class_name);
            $this->$class_name->handle();
            $this->success($job->id);
        } catch (Exception $e) {
            $this->fail($job->id);
        }

        if (isset($this->$class_name)) {
            unset($this->$class_name);
        }
    }

    private function success($job_id)
    {
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => 2
        ]);
    }

    private function fail($job_id)
    {
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => 3
        ]);
    }
}
