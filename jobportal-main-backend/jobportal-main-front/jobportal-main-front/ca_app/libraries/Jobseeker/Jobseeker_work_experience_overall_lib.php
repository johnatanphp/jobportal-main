<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_work_experience_overall_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_all($document_numbers = [], $country_id = 56)
    {  
        $list_country = [
            56, //Peru
        ];

        if (!in_array($country_id, $list_country)) {
            return [];
        }

        $this->load->model('Job_seeker');
        $this->db->from('tbl_recruitment_period_rules');
		$this->db->order_by('start', 'ASC');
		$period_rules = $this->db->get()->result();		

        $work_experiences = $this->get_cache_work_experiences($document_numbers);
        $results = [];

        foreach ($work_experiences as $document_number => $experiences) {
            $results[$document_number] = $this->get_info_alert($experiences, $period_rules);
        }

        return $results;
    }

    private function get_info_alert($experiences, $period_rules)
    {
        $timestamp = 0;
        $is_active = false;

        foreach ($experiences as $row) {
            
            if ($row->employee_status != 'CESADO') {
                //$is_active = true;
                //break;
                continue;
            }
        
            $timestamp+= (strtotime(str_replace('/', '-', $row->date_termination)) - strtotime(str_replace('/', '-', $row->date_admission)));
        }

        $period_text = '';
        $period_color = '#eeeeee';

        if ($is_active) {
            $period_text = 'Activo en Overall';
            $period_color = '#c9f98e';
        }

        if (!$is_active) {
            $years = round(($timestamp / (365 * 60 * 60 * 24)), 2);

            foreach ($period_rules as $rule) {

                if (($rule->start <= $years && $rule->end >= $years) || 
                    ($rule->start <= $years && $rule->end == null)) {

                    $period_color = $rule->color;
                    break;
                }
            }

            $years  = floor($timestamp / (365 * 60 * 60 * 24));
            $months = floor(($timestamp - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

            $period_text = $years . ' año(s) y ' . $months . ' mes(es)';
        }

        return [
            'experiences' => $experiences,
            'work_exp_time_description' => $period_text,
            'work_exp_time_color' => $period_color
        ];
    }

    private function get_cache_work_experiences($document_numbers)
    {
        $cache_seeker_work_experiences = $this->session->tempdata('cache_alert_seeker_work_experiences') ? 
            $this->session->tempdata('cache_alert_seeker_work_experiences') : 
            [];

        $document_number_to_search = [];
        foreach ($document_numbers as $doc_number) {
            
            if (isset($cache_seeker_work_experiences[$doc_number])) { 
                continue;
            }

            $document_number_to_search[] = $doc_number;
        }   

        $work_experiences = [];

        if (count($document_number_to_search) > 0) {
            $work_experiences = $this->build_data_work_experiences($document_number_to_search);
        }
    
        $work_experiences = $cache_seeker_work_experiences + $work_experiences;

        $this->session->set_tempdata('cache_alert_seeker_work_experiences', $work_experiences, 3600);

        return $work_experiences;
    }

    private function build_data_work_experiences($document_numbers)
    {
        $work_experiences =  $this->Job_seeker->get_overall_work_experiences($document_numbers);

        $results = [];

        foreach ($document_numbers as $doc_number) {
            $results[$doc_number] = $work_experiences[$doc_number] ?? [];
        }

        return $results;
    }

    public function get_accumulated_working_days($document_numbers = [])
    {
        $doc_numbers = [];
        $result_working_days = [];
       
        $results_work_experiences = $this->get_cache_work_experiences($document_numbers);

        foreach ($document_numbers as $doc_number) {

            $work_experiences = $results_work_experiences[$doc_number] ?? [];

            if (count($work_experiences) == 0) {
                continue;
            }

            $working_days = 0;

            //Sumar tiempo de trabajo en overall 
            foreach ($work_experiences as $row_exp) {
                if (!$row_exp->date_termination) {
                    continue;
                }

                $date_admission = date('Y-m-d', strtotime(str_replace('/', '-', $row_exp->date_admission)));
                $date_termination = date('Y-m-d', strtotime(str_replace('/', '-', $row_exp->date_termination)));

                $dt_date_admission = new DateTime($date_admission);
                $dt_date_termination = new DateTime($date_termination);

                $dt_diff = $dt_date_admission->diff($dt_date_termination);
                
                $working_days+=$dt_diff->days;
            }

            $result_working_days[$doc_number] = $working_days;
        }

        return $result_working_days;
    }
}
