<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Screening_jobseeker_pdf
{   
    private $pdf = null;

    private $screening = null;

    private $screening_type = null;
    
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function build($screening_id, $display_type = 0)
    {
        $this->pdf = new Mpdf([
            'c', 
            'A4',
            //'Legal',
            'default_font_size' => 8,
            'default_font' => 'Open Sans',
            'margin_bottom' => 25,
            'margin_top' => 15,
            'margin_footer' => 5
        ]);

        $screening = $this->db->get_where('tbl_screening', ['id' => $screening_id])->row();

        if (!$screening) {
            show_404();
        }
        
        $screening_type = $this->db->get_where('tbl_screening_types', ['id' => $screening->type_id])->row();

        $result = json_decode($screening->response);

        $data['screening'] = $screening;
        $data['result_data'] = $result->data;

        $html = '';

        if ($screening->type_id == '1') {
            if ($display_type == 0) {
                $html = $this->load->view('employer/screening/template/screening_basic', $data, true);
            }

            if ($display_type == 1) {
                $html = $this->load->view('employer/screening/template/screening_basic_preliminary', $data, true);
            }

            if ($display_type == 2) {
                $html = $this->load->view('employer/screening/template/screening_basic_annexes', $data, true);
            }
        }

        if ($screening->type_id == '2') {

            if ($display_type == 0) {
                $html = $this->load->view('employer/screening/template/screening_vip', $data, true);
            }

            if ($display_type == 1) {
                $html = $this->load->view('employer/screening/template/screening_vip_preliminary', $data, true);
            }

            if ($display_type == 2) {
                $html = $this->load->view('employer/screening/template/screening_vip_annexes', $data, true);
            }
        }

        $this->pdf->SetWatermarkText('NO IMPRIMIR');
        $this->pdf->showWatermarkText = true;
        $this->pdf->watermarkTextAlpha = 0.1;

        // $this->pdf->SetWatermarkImage(FCPATH . '/public/images/no-print.png');
        // $this->pdf->showWatermarkImage = true;

        $this->pdf->SetHTMLFooter('
            <p style="font-size:9px;color:#e04b4f;">
                ES FALTA GRAVE Y CAUSAL DE DESPIDO HACER USO Y/O ENTREGA A TERCEROS DE INFORMACIÓN RESERVADA Y CONFIDENCIAL DEL EMPLEADOR.
            </p>
            <p style="font-size:9px;padding-top:5px;">
                Este documento le pertenece a la empresa que ha solicitado la información de forma privada, ningún empleado deberá compartir ni exhibir esta información.
            </p>
            <p style="font-size:9px;padding-top:5px;">
                El proceso de seleccion de personal y contrataciones en compras, no esta obligado a revelar los motivos de su NO SELECCIÓN.
            </p>
            <p style="font-size:9px;">
                El presente informe esta sujeto al Decreto Legislativo N° 728 - LEY DE PRODUCTIVIDAD Y COMPETENCIA LABORAL ART. 25 INCISO D.
            </p>
        ');

        $this->pdf->WriteHTML($html);

        $this->pdf->SetDisplayMode('fullpage');

        $this->screening = $screening;
        $this->screening_type = $screening_type;
    }

    public function show($screening_id, $display_type = 0)
    {   
        $this->build($screening_id, $display_type);
        $this->pdf->SetTitle('Screening ' . $this->screening_type->name);
        $this->pdf->Output();
    }

    public function getOutput($screening_id)
    {
        $this->build($screening_id);
        return $this->pdf->Output('', 'S');
    }

    public function save($screening_id, $file_path)
    {
        $this->build($screening_id);
        $this->pdf->Output($file_path, 'F');
    }
}
