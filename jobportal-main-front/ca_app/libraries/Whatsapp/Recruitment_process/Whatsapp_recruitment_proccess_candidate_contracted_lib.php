<?php
class Whatsapp_recruitment_proccess_candidate_contracted_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($request_id, $candidate_id)
    {
        $candidate = $this->Job_seeker->find($candidate_id);

        if (!$candidate) {
            return false;
        }   

        $mobile = format_mobile($candidate->mobile);

        if (!$mobile) {
            return false;
        }

        $request = $this->Staff_request->find($request_id);

        if (!$request) {
            return false;
        }

        $job = $this->Posted_job->find([
            'request_ID' => $request->ID
        ]);

        if (!$job) {
            return false;
        }

        $this->load->model('Recruitment_document_request');
        $link_document = $this->Recruitment_document_request->create_link($candidate->ID, $job->ID);

        if (!$link_document) {
            return false;
        }

        $url = $this->config->item('hrm_api2_url') . '/whatsapp/recruitment_candidate/contracted/' . $mobile;

        $params = [
            'candidate_name' => trim($candidate->first_name),
            'position' => trim($job->job_title),
           'link_detail' => $job && $job->job_slug ? site_url('jobs/' . $job->job_slug) : site_url('login')
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_TIMEOUT => 30 //Segundos
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response == false) {
            return false;
        }

        $response_api = @json_decode($response);

        if (isset($response_api->error) && $response_api->error == 0) {
            return true;
        }

        return false;
    }
}
