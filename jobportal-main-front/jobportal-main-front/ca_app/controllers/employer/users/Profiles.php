<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profiles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        $this->load->model('Employer_profile');
    }

    public function index($user_id = 0)
    {
        $user_info = $this->Employer->find($user_id);

        if (!$user_info) {
            show_404();
        }

        $user_session = $this->Employer->find($this->session->userdata('user_id'));

        if ($user_session->company_ID != $user_info->company_ID) {
            show_404();
        }

        $company = $this->Company->get_company_by_id($user_info->company_ID);

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Perfiles del usuario';
        $data['user_info'] = $user_info;

        $this->db->select([
            'profiles.id AS profile_id',
            'profiles.name AS profile_name',
            'app_user_profiles.user_id AS profile_user_id'
        ]);

        $this->db->from('tbl_profiles profiles');
        $this->db->join(
            'tbl_employer_profiles app_user_profiles', 
            'app_user_profiles.profile_id=profiles.id AND app_user_profiles.user_id=' . $user_id, 
            'left'
        );
        $this->db->where('profiles.active', 1);

        if (!$company->system_internal) {
            $this->db->where('profiles.id', 1);
        }

        $data['profiles'] = $this->db->get()->result();
        
        $this->load->view('employer/users/profiles_view', $data);
    }  

    public function change_profile()
    {
        $user_id = $this->input->post('user_id'); 
        $profile_id = $this->input->post('profile_id');
        $sts = $this->input->post('sts') ? 1 : 0;

        $row_user = $this->Employer->find($user_id);

        if (!$row_user) {
            return false;
        }

        $user_session = $this->Employer->find($this->session->userdata('user_id'));

        if ($user_session->company_ID != $row_user->company_ID) {
            return false;
        }

        $this->Employer_profile->delete($user_id, $profile_id);

        if ($sts) {
            $this->Employer_profile->add([
                'user_id' => $user_id,
                'profile_id' => $profile_id
            ]);
        }

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        echo json_encode([
            'success' => $trans_status
        ]);
    } 
}
