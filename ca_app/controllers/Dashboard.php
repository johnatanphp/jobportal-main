<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_auth();
    }

    private function check_auth()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function jobseeker()
    {
        $user_type = $this->session->userdata('user_type');
        if ($user_type !== 'jobseeker') {
            redirect('dashboard/employer');
        }

        $data = [
            'applications_count' => 0,
            'saved_jobs' => 0,
            'interviews' => 0
        ];

        $this->load->view('jobseeker/dashboard', $data);
    }

    public function employer()
    {
        $user_type = $this->session->userdata('user_type');
        if ($user_type !== 'employer') {
            redirect('dashboard/jobseeker');
        }

        $data = [
            'jobs_posted' => 0,
            'applications_received' => 0,
            'candidates_interviewed' => 0
        ];

        $this->load->view('employer/dashboard', $data);
    }

    public function admin()
    {
        $user_type = $this->session->userdata('user_type');
        if ($user_type !== 'admin') {
            redirect('login');
        }

        $data = [
            'total_users' => 0,
            'total_employers' => 0,
            'total_jobs' => 0,
            'total_applications' => 0
        ];

        $this->load->view('admin/dashboard', $data);
    }
}
?>
