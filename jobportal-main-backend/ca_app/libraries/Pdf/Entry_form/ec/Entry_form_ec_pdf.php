<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Entry_form_ec_pdf
{   
    private $pdf = null;

    private $title;
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->load->model('Entry_form_ec');
    }

    private function build($entry_form_id)
    {
        $this->pdf = new Mpdf([
            'c', 
            'A4',
            //'Legal',
            'default_font_size' => 12,
            'default_font' => 'Open Sans'
        ]);

        $entry_form = $this->Entry_form_ec->get_by_entry_form_id($entry_form_id);
        $rightful_claimants = $this->Entry_form_ec->get_rightful_claimants($entry_form_id);

        $data = [
            'entry_form' => $entry_form,
            'rightful_claimants' => $rightful_claimants,
        ];
    
        $html = $this->load->view('jobseeker/entry_form/ec/common/entry_form_view', $data, true);

        $this->title = "Overall - Ficha Ingreso - " . $entry_form->identity_document_number . " - ". $entry_form->first_name;

        $this->pdf->WriteHTML($html);
        $this->pdf->SetDisplayMode('fullpage');
    }

    public function show($entry_form_id)
    {   
        $this->build($entry_form_id);
        $this->pdf->Output();
    }

    public function getOutput($entry_form_id)
    {
        $this->build($entry_form_id);
        return $this->pdf->Output('', 'S');
    }

    public function download($entry_form_id)
    {
        $this->build($entry_form_id);

        return $this->pdf->Output($this->title . ".pdf", 'D');
    }

    public function save($entry_form_id, $file_path)
    {
        $this->build($entry_form_id);
        $this->pdf->Output($file_path, 'F');
    }
}
