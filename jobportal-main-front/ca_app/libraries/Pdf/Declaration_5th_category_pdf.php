<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Declaration_5th_category_pdf
{   
    private $pdf = null;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function build($seeker_id)
    {
        $this->pdf = new Mpdf([
            'c', 
            'A4',
            'default_font_size' => 12,
            'default_font' => 'rubik'
        ]);

        $data['seeker'] = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

        $html = $this->load->view('jobseeker/requested_documents/common/declaration_5th_category_cert', $data, true);

        $this->pdf->WriteHTML($html);
        $this->pdf->SetDisplayMode('fullpage');
    }

    public function show($seeker_id)
    {   
        $this->build($seeker_id);
        $this->pdf->Output();
    }

    public function getOutput($seeker_id)
    {
        $this->build($seeker_id);
        return $this->pdf->Output('', 'S');
    }

    public function save($seeker_id, $file_path)
    {
        $this->build($seeker_id);
        $this->pdf->Output($file_path, 'F');
    }
}
