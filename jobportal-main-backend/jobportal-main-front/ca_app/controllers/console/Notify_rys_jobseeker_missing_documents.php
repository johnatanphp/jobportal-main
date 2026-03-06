<?php
require_once ("App_console.php");

class Notify_rys_jobseeker_missing_documents extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->load->library(
            'Exports/Rys_seeker_missing_documents_export', 
            null, 
            'Rys_seeker_missing_documents_export'
        );

        $this->load->library(
            'Storage_lib', 
            null, 
            'Storage_lib'
        );

        $this->db->select([
            'e.ID',
            'e.first_name',
            'e.email'
        ]);

        $this->db->from('tbl_employers e');
        $this->db->join('tbl_employer_profiles ep', 'ep.user_id=e.ID');
        $this->db->join('tbl_recruitment_rrhh_assignments rra', 'rra.rrhh_user_ID=e.ID');
        $this->db->where('e.company_ID', 1);
        $this->db->where('ep.profile_id', 3);
        
        $this->db->group_by('e.ID');

        $result_users = $this->db->get()->result();

        foreach ($result_users as $user) {

            $rrhh_user_id = $user->ID;

            $this->db->select([
                'rc.seeker_ID AS seeker_id',
                'rc.job_ID AS job_id'
            ]);

            $this->db->from('tbl_recruitment_rrhh_assignments rra');
            $this->db->join('tbl_recruitment_candidates rc', 'rc.job_ID=rra.job_ID');
            $this->db->where('rra.rrhh_user_ID', $rrhh_user_id);
            $this->db->where('rc.stage', 7);
            $this->db->where('rc.contracted', 0);
            $this->db->where('rc.discarded', 0);
            $result_seekers = $this->db->get()->result();

            $seeker_ids = [];

            foreach ($result_seekers as $key => $seeker_row) {
                $seeker_ids[] = $seeker_row->seeker_id;
            }  

            if (count($seeker_ids) == 0) {
                continue;
            }

            $report_ref = $this->Rys_seeker_missing_documents_export->build([
                'seeker_ids' => $seeker_ids
            ]);

            if (!$report_ref) {
                continue;
            }
    
            $filename = md5(uniqid('reporte-documentos-pendientes', true)) . '.xlsx';

            $report_local_path = 'public/uploads/tmp/' . $filename;

            $this->Rys_seeker_missing_documents_export->save($report_local_path);

            $report_path = 'files/' . md5(uniqid(true)) . '/reporte-documentos-pendientes.xlsx';

            try {
                $report_path = $this->Storage_lib->put($report_path,  FCPATH . $report_local_path);

            } catch (\Exception $e) {
                $report_path = false;
            } 

            if ($report_path === false) {
                continue;
            }

            $this->Storage_lib->setVisibility($report_path, 'public');

            $url_link = file_url($report_path);

            $data_email = [
                'name' => $user->first_name, 
                'url_link' => $url_link
            ];

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($user->email);

            $mail_message = load_email_view('email/notify_rrhh_seeker_missing_documents', $data_email);

            $this->email->subject('Reporte documentos de contratación por subir al portal');
            $this->email->message($mail_message);     
            $this->email->send();
        }
    }
}
