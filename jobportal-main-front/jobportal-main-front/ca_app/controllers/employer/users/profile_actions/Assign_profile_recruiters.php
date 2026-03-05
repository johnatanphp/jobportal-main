<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assign_profile_recruiters extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        //Load model
        $this->load->model('Business_unit');
    }

    public function index($user_id)
    {
        $data['title'] = 'Perfil del solicitante';
        $data['ads_row'] = $this->ads;

        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }
        
        $data['app_user_info'] = $user;
        $data['recruiter_info'] = $user;

        $data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $user->company_ID]);
        $data['client_companies'] = $this->Employer->get_client_companies($user->ID);

        $this->load->view('employer/users/profile_actions/assign_profile_recruiter_view', $data);
    }   

    public function save($user_id)
    {
        $data_input = $this->input->post();

        $user_id = $data_input['user_id'];
        $row_user = $this->Employer->find($user_id);

        if (!$row_user) {
            echo json_encode([
                'status' => false,
                'message' => 'Usuario no existe'
            ]);
            return;
        }

        $this->db->where('ID', $user_id);
        $this->db->update('tbl_employers', [
            'type' => $data_input['type_recruiter'] != '' ? $data_input['type_recruiter'] : null 
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Perfil se ha actualizado'
        ]);
    }
}
