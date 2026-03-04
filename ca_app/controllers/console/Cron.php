<?php
require_once ("App_console.php");

class Cron extends App_console  
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Cron_schedule_lib', null, 'Cron_schedule_lib');  
    }

    public function init()
    {
        /** Cron app **/
    
        $this->Cron_schedule_lib->run("Tray_candidates_process_hiring", "*/3 * * * *");
        $this->Cron_schedule_lib->run("Exam_request_update_hrm", "*/8 * * * *");
        $this->Cron_schedule_lib->run("Notify_entry_jobseekers", "*/10 * * * *");
        $this->Cron_schedule_lib->run("Migration_hrm_workflow_overall", "*/10 * * * *");   
        $this->Cron_schedule_lib->run("Screening_update_process", "*/10 * * * *"); 
        $this->Cron_schedule_lib->run("Migration_sap_workflow_overall", "*/20 * * * *");
        $this->Cron_schedule_lib->run("Send_recruitment_tray_candidate_links", "*/10 * * * *");
        $this->Cron_schedule_lib->run("Recruitment_process_finish", "*/30 * * * *");  
        $this->Cron_schedule_lib->run("Screening_batch_process", "*/6 * * * *"); 
        
        // $this->Cron_schedule_lib->run("Tray_candidates_process_retry_hiring", "*/5 * * * *");
        // $this->Cron_schedule_lib->run("Migration_workflow_employer_permission_type_recruiters", "*/5 * * * *");
        // $this->Cron_schedule_lib->run("Migration_workflow_employer_permission_type_rrhh", "*/8 * * * *");
        // $this->Cron_schedule_lib->run("Migration_workflow_employer_permissions", "*/20 * * * *");
        // $this->Cron_schedule_lib->run("Migration_staff_request_canceled", "*/5 * * * *");  
        // $this->Cron_schedule_lib->run("Update_seeker_entries_filters", "*/15 * * * *");
        // $this->Cron_schedule_lib->run("Send_job_offers_to_seeker_fits", "*/5 * * * *");
        // $this->Cron_schedule_lib->run("Register_rrhh_responsibles", "*/10 * * * *");
        // $this->Cron_schedule_lib->run("Update_refact_contract", "*/5 * * * *");   
    }
}
