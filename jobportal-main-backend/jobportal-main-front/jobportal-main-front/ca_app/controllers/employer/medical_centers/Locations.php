<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Locations extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load Models
        $this->load->model('Medical_center_location');
    }

    public function index()
    {
        $locations = $this->Medical_center_location->all([
            'medical_center_code' => $this->input->get('mc_code')
        ]);

        echo json_encode([
            'data' => $locations
        ]);   
    }

    public function create()
    {
        $data =[
            'medical_center_code' => $this->input->post('code'),
            'location' => $this->input->post('name'),
            'direction' => $this->input->post('address'),
            'ubication' => $this->input->post('city'),
            'active' => 1
        ];
        $location_id = $this->Medical_center_location->create($data);

        if ($location_id) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'No se pudo agregar la sede'
            ]);
        }
    }

    public function edit()
    {
        $data =[
            'location' => $this->input->post('name'),
            'direction' => $this->input->post('address'),
            'ubication' => $this->input->post('city'),
            'active' => $this->input->post('status')
        ];
        
        $trans_status = $this->Medical_center_location->update($this->input->post('id'), $data);

        if ($trans_status) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'No se pudo actualizar la sede'
            ]);
        }
    }
}
