<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Job_profile_detail_pdf
{   
    private $pdf = null;
    private $job_profile;
    private $filters;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function build($filters = [])
    {
        $this->pdf = new Mpdf([
            'c', 
            'A4',
            //'Legal',
            'default_font_size' => 12,
            'default_font' => 'Open Sans'
        ]);

        $this->pdf->SetTitle('DESCRIPCIÓN DEL PUESTO');

        $data['rys_stages'] = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        $jp_id = $filters['id'];
        $job_profile = $this->Job_profile->find($jp_id);
        $company = $this->Company->find($job_profile->company_id);

        $data['job_profile'] = $job_profile;
        $data['job_profile_responsibilities'] = $this->Job_profile->get_responsibilities_by_job_profile_id($jp_id);
        $data['job_profile_skills'] = $this->Job_profile->get_skills_by_job_profile_id($jp_id);
        $data['disability_values'] = $this->Job_profile->disability_values($jp_id);

        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => @$job_profile->job_charge_ID
        ])->result();
        
        $data['results_disability'] = $this->Job_profile->get_results_disability($jp_id);
		$data['disability_values'] = $this->Job_profile->disability_values($jp_id);
		$data['disability_eligibles'] = $this->Job_profile->get_disability_eligibles($jp_id);
		$data['jp_factor_valuations'] = $this->Job_profile->get_factor_valuations($jp_id);
		$data['jp_factor_total_score'] = $this->Job_profile->get_factor_total_score($jp_id);
        $data['jp_benefits'] = $this->Job_profile->get_benefits($job_profile->company_id, $jp_id);
        $data['country'] = $this->Country->find($company->country_id);
        $data['occupational_group'] = $this->Job_charge->find($job_profile->job_charge_ID);
        $data['export_pdf'] = true;
        $data['export_filters'] = $filters;
  
        $html = $this->load->view(
            'employer/job_profile/common/job_profile_detail', 
            $data, 
            true
        );

        $this->pdf->WriteHTML($html);
        $this->pdf->SetDisplayMode('fullpage');
    }

    public function show($filters)
    {   
        $this->build($filters);
        $this->pdf->Output();
    }

    public function getOutput($filters)
    {
        $this->build($filters);
        return $this->pdf->Output('', 'S');
    }

    public function save($filters, $file_path)
    {
        $this->build($filters);
        $this->pdf->Output($file_path, 'F');
    }
}
