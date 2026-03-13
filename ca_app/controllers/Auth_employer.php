<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_employer extends CI_Controller
{       
        public function __construct()
        {
        parent::__construct();
                $this->ads = $this->Ad->get_ads();

                //Load libraries
                $this->load->library(
                        'App/Session/Session_employer', 
                        null, 
                        'Session_employer'
                );
                $this->load->library(
                        'App/Auth/Auth_employer_login', 
                        null, 
                        'Auth_employer_login'
                );
    }
        
        public function login()
        {       
        if ($this->session->userdata('user_id')) {
                redirect($this->session->userdata('user_dashboard'));
                exit;
        }

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Iniciar sesión como empresa - ' . SITE_NAME;
        $data['msg'] = '';
    
        $this->form_validation->set_rules('company_email', 'Email', 'trim|required');
                $this->form_validation->set_rules('company_pass', 'Contraseña', 'trim|required');
                $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

        if ($this->form_validation->run() === FALSE) {
                $data['msg'] = $this->session->flashdata('msg');
                $this->load->view('employer_login_view', $data);
                return;
        }
        
                $data_login = $this->Auth_employer_login->login(
                        $this->input->post('company_email'), 
                        $this->input->post('company_pass')
                );

                if ($data_login['success'] === true) {
                
                        $user = $this->Employer->find($data_login['data']['user_id']);

            if ($user) {
                $this->Session_employer->create($user);
            }
                
                        $redirect = $this->session->userdata('back_from_user_login') ? $this->session->userdata('back_from_user_login') : $this->session->userdata('user_dashboard');
                        $this->session->set_userdata('back_from_user_login', '');
                                
                        echo json_encode([
                                'success' => true,
                                'message' => $data_login['message'],
                                'redirect' => site_url($redirect)
                        ]);

                        return;
                }

                echo json_encode([
                        'success' => false,
                        'message' => $data_login['message']
                ]);
        }
}
