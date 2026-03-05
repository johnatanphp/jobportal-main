<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_offers extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        show_404();
    }

    public function notify_view()
    {
        $employer = $this->Employer->find($this->session->userdata('user_id'));

        $jobs = $this->Posted_job->search_opened_posted_jobs_by_company_id($employer->company_ID, [], 1000, 0);

        $data['jobs'] = $jobs;
        $data['seeker_ids'] =  $this->input->post('seeker_ids');
        
        $this->load->view('employer/recruitment_jobseeker_fits/job_offers/common/content_job_offers', $data);
    }

    public function notify()
    {
        $employer = $this->Employer->find($this->session->userdata('user_id'));
        
        $job_id = $this->input->post('job_id');
        $seeker_ids = $this->input->post('seeker_ids');

        $seeker_data = [];

        foreach ($seeker_ids as $seeker_id) {
            
            $seeker_data[] = [
                'created_at' => date('Y-m-d H:i:s'),
                'seeker_id' => $seeker_id,
                'job_id' => $job_id,
                'offer_sent_by' => $employer->ID,
                'offer_sent' => 0
            ];
        }

        if (count($seeker_data) == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'No existen datos para registrar'
            ]);
            return;
        }

        //Insertar lote de registros
        $this->db->insert_batch('tbl_seeker_entries_job_offers', $seeker_data);

        echo json_encode([
            'status' => true,
            'message' => 'Notificación en curso, en algunos minutos se estará enviando a los candidatos'
        ]);
    }

    public function seeker_offers()
    {
        $seeker_id = $this->input->get('seeker_id');

        $this->db->select([
            'jobs.ID AS job_id',
            'jobs.job_title',
            'jobs.job_slug',
            'job_offers.offer_sent',
            'job_offers.offer_sent_date',
            'employer.first_name AS employer_name',
            'job_offers.mobile'
        ]);
        $this->db->from('tbl_seeker_entries_job_offers job_offers');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=job_offers.job_id');
        $this->db->join('tbl_employers employer', 'employer.ID=job_offers.offer_sent_by');
        $this->db->where('job_offers.seeker_id', $seeker_id);
        $this->db->order_by('job_offers.offer_sent_date', 'DESC');
        $job_offers = $this->db->get()->result();

        $data['job_offers'] = $job_offers;

        $this->load->view(
            'employer/recruitment_jobseeker_fits/job_offers/common/content_job_offer_send', 
            $data
        );
    }
}
