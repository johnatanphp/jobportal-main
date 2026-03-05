<?php
require_once ("App_console.php");

class Send_job_offers_to_seeker_fits extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'offers.id AS offers_id',
            'jobs.job_title',
            'jobs.job_slug',
            'jobs.city AS job_city',
            'seekers.mobile',
            'seekers.first_name',
            'seekers.last_name',
            'seeker_entries.entry_key AS entry_token',
            'request.cost_center'
        ]);
        $this->db->from('tbl_seeker_entries_job_offers offers');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=offers.job_id');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=offers.seeker_id');
        $this->db->join('tbl_seeker_entries seeker_entries', 'seeker_entries.seeker_id=seekers.ID');
        $this->db->join('tbl_staff_requests request', 'jobs.request_ID=request.ID', 'left');
        $this->db->where('offers.offer_sent', 0);
        $this->db->where('offers.offer_sent_error', 0);
        $this->db->limit(50);
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
            $this->send_offer($row);
        }
    }

    private function send_offer($data)
    {
        $curl = $this->whatsapp_send_offer([
            'mobile' => $data->mobile,
            'full_name' => trim($data->first_name),
            'position' => $data->job_title,
            'address' => $data->job_city,
            'link_offer_1' => site_url('jobseeker_update_entry?t=' . $data->entry_token),
            'link_offer_2' => site_url('jobs/' . $data->job_slug  . '?source=jobseeker_fits'),
            'ccosto' => $data->cost_center ? $data->cost_center : null 
        ]);

        if ($curl->error) {
            $this->db->where('id', $data->offers_id);
            $this->db->update('tbl_seeker_entries_job_offers', [
                'offer_sent_error' => 1,
                'offer_sent_log' => $curl->errorMessage,
            ]);
            return;
        }

        $response = $curl->response;

        if ($response->error) {
            $this->db->where('id', $data->offers_id);
            $this->db->update('tbl_seeker_entries_job_offers', [
                'offer_sent_error' => 1,
                'offer_sent_log' => json_encode($response),
            ]);
            return;
        }

        $this->db->where('id', $data->offers_id);
        $this->db->update('tbl_seeker_entries_job_offers', [
            'mobile' => $data->mobile,
            'offer_sent' => 1,
            'offer_sent_error' => 0,
            'offer_sent_log' => json_encode($response),
            'offer_sent_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function whatsapp_send_offer(
        $parameters
     ) {  
        $mobile = format_mobile(str_replace('+', '', $parameters['mobile']));

        if (!$mobile) {
            return false;
        }

        $url = $this->config->item('hrm_api2_url') . '/whatsapp/employee/offer/' . $mobile;

        $curl = new \Curl\Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setTimeout(5);
        $curl->post($url, $parameters);

        return $curl; 
    }
}
