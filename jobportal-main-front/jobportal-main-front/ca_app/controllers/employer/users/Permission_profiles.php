<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission_profiles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        $this->load->model('Profile_actions_permission');
        $this->load->model('Profile');
    }

    public function index($profile_id = 0, $employer_id = 0)
    {
        $params = $this->input->post();

        $employer = $this->Employer->find($employer_id);
        $profile = $this->Profile->find($profile_id);

        if (count($params) == 0) {

            $result_modules_actions = $this->Profile_actions_permission->get_employer_actions($profile_id, $employer_id);
            $module_actions = [];
    
            foreach ($result_modules_actions as $module_action) {
                $module_actions[$module_action->module_id]['name'] = $module_action->module_name;
                $module_actions[$module_action->module_id]['actions'][] = $module_action;
            }

            $data = [
                'title' => 'Permisos Perfiles',
                'ads_row' => $this->ads,
                'employer' => $employer,
                'module_actions' => $module_actions,
                'profile' => $profile
            ];
            $this->load->view('employer/users/permission_profiles/permissions', $data);
            return;
        }

        $action_permissions = $params['action_permissions'] ?? [];

        $this->db->where('employer_id', $employer->ID);
        $this->db->where('profile_id', $profile->id);
        $this->db->delete('tbl_profile_actions_permissions');

        foreach ($action_permissions as $action_permission_id) {

            $this->db->insert('tbl_profile_actions_permissions', [
                'profile_id' =>  $profile->id,
                'employer_id' => $employer->ID,
                'action_id' => $action_permission_id
            ]);
        }

        echo json_encode([
            'status' => true,
            'message' => 'Permisos han sido guardados'
        ]);
    }
}
