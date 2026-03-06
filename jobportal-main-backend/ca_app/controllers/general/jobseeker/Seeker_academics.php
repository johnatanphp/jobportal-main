<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Seeker_academics extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models

        $this->load->model('Institution');
        $this->load->model('Career');
    }

    public function get_institutions()
    {
        $institution_educ_type = trim($this->input->get('institution_educ_type'));
        $institution_type = trim($this->input->get('institution_type'));

        $results = $this->Institution->all([
            'institution_educational_type_id' => $institution_educ_type,
            'institution_type_id' => $institution_type,
            'active' => 1
        ]);

        echo json_encode([
            'data' => $results
        ]);
    }

    public function get_careers()
    {
        $institution = trim($this->input->get('institution'));      
        $results = $this->Career->get_all_by_institution_code($institution);

        echo json_encode([
            'data' => $results
        ]);
    }
}
