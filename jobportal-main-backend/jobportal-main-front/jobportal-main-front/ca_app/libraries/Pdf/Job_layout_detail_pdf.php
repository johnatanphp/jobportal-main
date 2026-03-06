<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Job_layout_detail_pdf
{   
    private $pdf = null;
    private $job_layout;
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
        $job_layout = $this->Job_layout->find($jp_id);
        $company = $this->Company->find($job_layout->company_id);

        $data['job_layout'] = $job_layout;
        $data['jl_responsibilities'] = $this->Job_layout->get_responsibilities_by_job_layout_id($jp_id);
        $data['jl_skills'] = $this->Job_layout->get_skills_by_job_layout_id($jp_id);
        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => @$job_layout->job_charge_id
        ])->result();
        
        $data['jl_disability_options'] = $this->Job_layout->get_results_disability_options($jp_id);
        //$data['jl_disability_values'] = $this->Job_layout->disability_values($jp_id);
        //$data['jl_results_disability'] = $this->Job_layout->get_results_disability($jp_id);
		//$data['jl_disability_eligibles'] = $this->Job_layout->get_disability_eligibles($jp_id);
		$data['jl_factor_valuations'] = $this->Job_layout->get_factor_valuations($jp_id);
		$data['jl_factor_total_score'] = $this->Job_layout->get_factor_total_score($jp_id);
        $data['jl_benefits'] = $this->Job_layout->get_benefits($job_layout->company_id, $jp_id);
        $data['country'] = $this->Country->find($company->country_id);
        $data['jl_occupational_group'] = $this->Job_charge->find($job_layout->job_charge_id);

        $data['export_pdf'] = true;
        $data['export_filters'] = $filters;
  
        $html = $this->load->view(
            'employer/job_layouts/common/job_layout_detail', 
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
