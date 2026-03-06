<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Medical_centers extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load Models
        $this->load->model('Medical_center');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {

            $medical_centers = [];
            $results = [];
            $medical_centers = $medical_centers->ProveedorOrden;
    
            foreach ($medical_centers as $row) {
                $results[] = [
                    'medical_center_code' => $row->COD_PROVEEDOR,
                    'medical_center_name' => $row->RAZON_SOCIAL
                ];
            }

            echo json_encode([
                'data' => $results
            ]);
            return;
        }

        $data['title'] = "Centros médicos";
        $data['ads_row'] = $this->ads;

        $this->load->view('employer/medical_centers/medical_centers/list', $data);
    }
}
