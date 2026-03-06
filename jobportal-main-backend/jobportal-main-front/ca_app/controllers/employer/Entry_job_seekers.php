<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry_job_seekers extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        $this->load->model('Entry_job_seeker');

        show_404();
        
        if (get_session_company_id() != 1) {
            show_404();
        }
    }

    public function all($job_id = 0)
    {
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Postulantes importados';
        
        $job_seekers = $this->Entry_job_seeker->search_all($job_id, -1, -1);
        $data['result_job_seekers'] = $job_seekers;
        $data['job'] = $job;
        $data['links'] = '';

        $this->load->view('employer/recruitment/imported_job_seekers_view', $data);
    }

    public function import($job_id = 0)
    {
        $this->load->model('Recruitment_process');

        $rys_process = $this->Recruitment_process->get_process_by_job_id($job_id);

        if ($rys_process && $rys_process->sts != 'active') {
            $data = [
                'heading' => 'Error',
                'message' => 'Este proceso no está activo'
            ];
            $this->load->view('errors/html/error_general', $data);
            return;
        }

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Importar postulantes';
        $data['job'] = $job;
        
        $this->load->view('employer/recruitment/entry_job_seekers_view', $data);
    }

    public function do_import()
    {
        $this->load->library(
            'Imports/Entry_seekers_other_site_import',
            null,
            'Entry_seekers_other_site_import'
        );

        $all_inputs = $this->input->post();
        $job_id = $all_inputs['job_id'];

        $import_data = $this->Entry_seekers_other_site_import->import(
            $all_inputs
        );

        if ($import_data['status'] == true) {
            $this->session->set_flashdata(
                'msg', 
                '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <b>Registros importados con éxito</b>
                </div>'
            );
            redirect('employer/entry_job_seekers/all/' .  $job_id);
        }

        if ($import_data['status'] == false) {
            $this->session->set_flashdata(
                'msg', 
                '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    ' . $import_data['message'] . '
                </div>'
            );

            redirect('employer/entry_job_seekers/import/' . $job_id);
        }
    }

    public function notify_entry_jobseeker_email()
    {   
        $email = $this->input->post('email');
        $this->Entry_job_seeker->notify_entry_to_jobseeker_by_email($email);

        echo json_encode([
            'status' => true
        ]); 
    }

    public function notify_entry_all_jobseekers_email()
    {   
        $job_id = $this->input->post('job_id');
        $this->Entry_job_seeker->notify_entry_to_not_activated_jobseekers_by_email($job_id);

        echo json_encode(array(
            'success' => true
        )); 
    }
}
