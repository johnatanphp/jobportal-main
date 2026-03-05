<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobseeker_forms_hook
{
    public function validate()
    {
        $CI = & get_instance();

        if (!$CI->config->item('form_1_is_required')) {
            return;
        }

        $is_jobseeker_login = $CI->session->userdata('is_job_seeker');

        if (!$is_jobseeker_login) {
            return;
        }

        $folder = $CI->uri->segment(1);

        if ($folder != 'jobseeker' || $CI->uri->segment(2) == 'legal_terms') {
            return;
        }

        $jobseeker_validation_url = $CI->session->userdata('jobseeker_validation_url');

        $data_url = explode('|', $jobseeker_validation_url);

        //Validate Data jobseeker
        $CI->session->unset_userdata('jobseeker_validation_url');

        if (count($_POST) > 0) {
            return;
        }

        if (in_array($CI->uri->segment(2), $data_url)) {
            return;
        }

        if (!is_jobseeker_data_complete()) {            
            $CI->session->set_userdata('jobseeker_validation_url', 'my_account');
            redirect('jobseeker/my_account');
        }

        //Validate forms
        if (in_array($CI->uri->segment(2) . '/' . $CI->uri->segment(3), $data_url)) {
            return;
        }

        $user_id = $CI->session->userdata('user_id');

        $CI->db->from('tbl_rys_form_seekers');
        $CI->db->where('form_id', 1);
        $CI->db->where('seeker_id', $user_id);

        $CI->db->order_by('assignment_id', 'DESC');

        $assignment = $CI->db->get()->row();

        if ($assignment && $assignment->answered) {
            return;
        }

        if (!$assignment) {
            $data = [
                'form_id' => 1,
                'seeker_id' => $user_id,
                'stage' => 2,
                'assignment_date' => date('Y-m-d H:i:s')
            ];

            $CI->db->insert('tbl_rys_form_seekers', $data);

            $assignment_id = $CI->db->insert_id();
        } else {
            $assignment_id = $assignment->assignment_id;
        }

        $CI->session->set_userdata('jobseeker_validation_url', 'forms/answer|forms/do_answer');
        redirect('jobseeker/forms/answer/' . $assignment_id);
    }
}
