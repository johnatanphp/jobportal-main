<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('App/Session/Session_admin', null, 'Session_admin');
        $this->load->library('App/Auth/Auth_admin_login', null, 'Auth_admin_login');
    }
    
    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('/');
            exit;
        }
        
        $data['title'] = 'Iniciar sesión - Administrador - ' . SITE_NAME;
        $data['msg'] = '';
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) {
            $data['msg'] = $this->session->flashdata('msg');
            $this->load->view('admin_login_view', $data);
            return;
        }
        
        $admin_login = $this->Auth_admin_login->login(
            $this->input->post('email'), 
            $this->input->post('pass')
        );
        
        if ($admin_login['success'] === true) {
            $user = $this->Admin->find($admin_login['data']['admin_id']);
            if ($user) {
                $this->Session_admin->create($user);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Ok',
                'redirect' => site_url('admin/dashboard')
            ]);
            return;
        }
        
        echo json_encode([
            'success' => false,
            'message' => $admin_login['message']
        ]);
    }
}
