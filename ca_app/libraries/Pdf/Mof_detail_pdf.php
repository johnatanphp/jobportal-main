<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Mof_detail_pdf
{   
    private $pdf = null;
    private $mof;
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

        $id = $filters['id'];
        $mof = $this->Mof->find($id);
        $company = $this->Company->find($mof->company_id);

        $data['mof'] = $mof;
        $data['mof_responsibilities'] = $this->Mof->get_responsibilities_by_mof_id($id);
        $data['mof_skills'] = $this->Mof->get_skills_by_mof_id($id);

        $data['mof_indicators'] = $this->Mof->get_indicators_by_mof_id($id);
        $data['disability_values'] = $this->Mof->disability_values($id);
        $data['mof_belonging_areas'] = $this->Mof->get_belonging_areas_by_mof_id($id);

        $data['go_skills'] = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => @$mof->job_charge_id
        ])->result();
        
        $data['results_disability'] = $this->Mof->get_results_disability($id);
		$data['disability_values'] = $this->Mof->disability_values($id);
		$data['disability_eligibles'] = $this->Mof->get_disability_eligibles($id);
		$data['mof_factor_valuations'] = $this->Mof->get_factor_valuations($id);
		$data['mof_factor_total_score'] = $this->Mof->get_factor_total_score($id);
        $data['mof_benefits'] = $this->Mof->get_benefits($mof->company_id, $id);
        $data['country'] = $this->Country->find($company->country_id);

        $data['export_pdf'] = true;
        $data['export_filters'] = $filters;
  
        $html = $this->load->view(
            'employer/mof/common/mof_detail', 
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
