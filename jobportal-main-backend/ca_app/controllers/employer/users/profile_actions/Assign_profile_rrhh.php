<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assign_profile_rrhh extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        $this->load->model('Employer_rrhh_type');
    }

    public function index($user_id)
    {
        $data['title'] = 'Perfil RRHH';
        $data['ads_row'] = $this->ads;

        $employer = $this->Employer->find($user_id);

        if (!$employer) {
            show_404();
        }

        $company = $this->Company->find($employer->company_ID);

        $data['employer'] = $employer;
        $data['rrhh_types'] = $this->Employer_rrhh_type->all(['active' => 1]);

        $this->load->view('employer/users/profile_actions/assign_profile_rrhh', $data);
    }   

    public function save($user_id)
    {
        $data_input = $this->input->post();

        $user_id = $data_input['user_id'];
        $employer = $this->Employer->find($user_id);

        if (!$employer) {
            echo json_encode([
                'status' => false,
                'message' => 'Usuario no existe'
            ]);
            return;
        }

        $this->db->where('ID', $user_id);
        $this->db->update('tbl_employers', [
            'rrhh_type_id' => $data_input['rrhh_type_id'] != '' ? $data_input['rrhh_type_id'] : null 
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Perfil se ha actualizado'
        ]);
    }
}
