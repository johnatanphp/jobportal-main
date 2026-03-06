<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Form_rtps_pdf
{   
    private $pdf = null;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function build($form_rtps)
    {
        $this->load->model('Jobseeker_form_rtps');
        $this->load->model('Posted_job');
        $this->load->model('Staff_request');

        $this->pdf = new Mpdf([
            'c', 
            'A4',
            //'Legal',
            'default_font_size' => 12,
            'default_font' => 'Open Sans'
        ]);

        $data['form_rtps'] = $form_rtps;
        $data['rtps_rightful_claimants'] = $this->Jobseeker_form_rtps->get_rightful_claimants_by_form_id(
            $form_rtps->ID
        );
        $data['company'] = $this->Jobseeker_form_rtps->get_form_company(
            $form_rtps->seeker_ID, $form_rtps->job_id
        );

        $job = $this->Posted_job->find($form_rtps->job_id);
        $staff_request = null;

        if ($job) {
            $staff_request = $this->Staff_request->find($job->request_ID);
        }

        $html = '';
        $html .= $this->load->view('employer/recruitment/common/candidate_form_rtps', $data, true);

        //Si la solicitud proviene de la unidad de negocio MK mostrar otros documentos
        if ($staff_request && $staff_request->cod_business_unit == 'MK') {
            $html .= '<div style="page-break-after: always;"></div>';
            $html .= $this->load->view('employer/recruitment/common/candidate_send_form_rtps', $data, true);
            $html .= '<div style="page-break-after: always;"></div>';
            $html .= $this->load->view('employer/recruitment/common/letter_engagement_form_rtps', $data, true);
            $html .= '<div style="page-break-after: always;"></div>';
            $html .= $this->load->view('employer/recruitment/common/loan_application_form_rtps', $data, true);
        }

        $this->pdf->WriteHTML($html);
        $this->pdf->SetDisplayMode('fullpage');
    }

    public function show($form_rtps)
    {   
        $this->build($form_rtps);
        $this->pdf->Output();
    }

    public function getOutput($form_rtps)
    {
        $this->build($form_rtps);
        return $this->pdf->Output('', 'S');
    }

    public function save($form_rtps, $file_path)
    {
        $this->build($form_rtps);
        $this->pdf->Output($file_path, 'F');
    }
}
