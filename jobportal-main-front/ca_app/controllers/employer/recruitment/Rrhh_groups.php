<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rrhh_groups extends CI_Controller 
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        if (!user_belong_to_company_internal()) {
            show_404();
        }
    }

    public function index()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = "Grupos RRHH";
        
        $user = $this->Employer->find($this->session->userdata('user_id'));
    
        $data['groups'] = $this->db->select([
            'groups.id',
            'groups.name',
            'COUNT(users.user_id) as count_users',
        ])
            ->from('tbl_recruitment_rrhh_groups groups')
            ->join(
                'tbl_recruitment_rrhh_group_users users',
                'groups.id=users.rrhh_group_id',
                'left'
            )
            ->where('groups.company_id', $user->company_ID)
            ->group_by(['groups.id'])
            ->get()
            ->result();

        $this->load->view('employer/recruitment/rrhh_groups/list', $data);
    }

    public function create()
    {
        $user = $this->Employer->find($this->session->userdata('user_id'));

        $data = [
            'name' => trim($this->input->post('name')),
            'company_id' => $user->company_ID,
            'active' => 1
        ];

        $this->db->insert('tbl_recruitment_rrhh_groups', $data);
        $id = $this->db->insert_id();

        echo json_encode([
            'success' => $id ? true : false
        ]);
    }

    public function edit()
    {
        $id = $this->input->post('id');

        $data = [
            'name' => trim($this->input->post('name')),
        ];

        $this->db->where('id', $id);
        $status = $this->db->update('tbl_recruitment_rrhh_groups', $data);

        echo json_encode([
            'success' => $status
        ]);
    }

    public function add_user()
    {
        $group_id = $this->input->post('group_id');
        $email = trim($this->input->post('email'));

        $user_auth = $this->Employer->find($this->session->userdata('user_id'));

        $user = $this->db->select([
            'users.*'    
        ])
        ->from('tbl_employers users')
        ->join('tbl_companies companies', 'companies.ID=users.company_ID')
        ->where('users.email', $email)
        ->where('users.company_id', $user_auth->company_ID)
        ->where('companies.system_internal', 1) //Que es un usuario interno de Overall
        ->get()
        ->row();
        
        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no existe como cuenta de empresa interna de Overall'
                
            ]);
            return;
        }

        if ($user->sts =! 'active') {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no esta activo'
                
            ]);
            return;
        }
            
        $profile_rrhh = $this->db->get_where('tbl_employer_profiles', [
            'profile_id' => 3, //ID RRHH
            'user_id' => $user->ID
        ])->row();

        if (!$profile_rrhh) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no tiene el perfil RRHH activo'
            ]);
            return;
        }

        $this->db->where('user_id', $user->ID);
        $this->db->where('rrhh_group_id', $group_id);
        $this->db->delete('tbl_recruitment_rrhh_group_users');

        $data_insert = [
            'user_id' => $user->ID,
            'rrhh_group_id' => $group_id
        ];
        $this->db->insert('tbl_recruitment_rrhh_group_users', $data_insert);

        $insert_id = $this->db->insert_id();

        echo json_encode([
            'success' => $insert_id ? true : false,
            'message' => 'Usuario agregado'
        ]);
    }

    public function get_users()
    {
        $group_id = $this->input->get('group_id');

        $users = $this->db->select([
            'group_users.id',
            'users.first_name',
            'users.last_name',
            'users.email'
        ])
        ->from('tbl_recruitment_rrhh_group_users group_users')
        ->join(
            'tbl_employers users',
            'users.ID=group_users.user_id'
        )
        ->where('group_users.rrhh_group_id', $group_id)
        ->get()
        ->result();

        echo json_encode([
            'data' => $users
        ]);
    }

    public function remove_user()
    {
        $this->db->where('id', $this->input->post('id'))
             ->delete('tbl_recruitment_rrhh_group_users');

        echo json_encode([
            'success' => true,
            'message' => 'Usuario ha sido quitado'
        ]);
    }
}
