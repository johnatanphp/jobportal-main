<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ubigeos extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }

    public function search_suggestions()
    {
        $country = trim($this->input->get('country'));
        $term = trim($this->input->get('term'));

        $suggestions = $this->Ubigeo->search_suggestions($country, $term, 10);
        echo json_encode($suggestions);
    }

    public function get_all()
    {
       $result_ubigeos = $this->Ubigeo->get_all_records();
        echo json_encode(array('ubigeos' => $result_ubigeos));
    }

    public function get_provinces_by()
    {
        echo json_encode([
            'data' => $this->Ubigeo->get_provinces_by($this->input->post('department'))
        ]);
    }

    public function get_districts_by()
    {
        echo json_encode([
            'data' => $this->Ubigeo->get_districts_by($this->input->post('province'))
        ]);
    }
}
