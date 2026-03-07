<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_seeker extends CI_Controller
{       
        public function __construct()
        {
        parent::__construct();
                $this->ads = $this->Ad->get_ads();

        //Load libraries
        $this->load->library(
            'App/Session/Session_job_seeker', 
            null, 
            'Session_job_seeker'
        );
        $this->load->library(
                        'App/Auth/Auth_job_seeker_login', 
                        null, 
                        'Auth_job_seeker_login'
                );
    }
        
        public function login()
        {       
                $this->load->library('Facebook/facebook_api_lib');
                $this->load->library('Linkedin/linkedin_api_lib');
        
                if ($this->session->userdata('user_id')) {
                        redirect($this->session->userdata('user_dashboard'));
                        exit;
                }
                
                $data['ads_row'] = $this->ads;
                $data['title'] = 'Iniciar sesión - ' . SITE_NAME;
                $data['msg'] = '';

                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required');
                $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

                $data['login_url_fb'] = $this->facebook_api_lib->get_login_url();
                $data['login_url_linkedin'] = $this->linkedin_api_lib->get_login_url();
                
                if ($this->form_validation->run() === FALSE) {
                        $data['msg'] = $this->session->flashdata('msg');
                        $this->load->view('login_view', $data);
                        return;
                }

                $seeker_login = $this->Auth_job_seeker_login->login(
                        $this->input->post('email'), 
                        $this->input->post('pass')
                );

                if ($seeker_login['success'] === true) {

            $user = $this->Job_seeker->find($seeker_login['data']['seeker_id']);

            if ($user) {
                $this->Session_job_seeker->create($user);
            }
           
            $redirect = ($this->session->userdata('back_from_user_login')) ? 
                        $this->session->userdata('back_from_user_login') : 
                        $this->session->userdata('user_dashboard');

            $this->session->set_userdata('back_from_user_login','');

            echo json_encode([
                'success' => true,
                'message' => 'Ok',
                'redirect' => site_url($redirect)
            ]);

            return;
                }

        echo json_encode([
            'success' => false,
            'message' => $seeker_login['message']
        ]);             
        }

        private function linkedin_login()
        {
                $this->load->library('Linkedin/linkedin_api_lib');

                $access_token = $this->linkedin_api_lib->get_access_token($_GET['code']);
                
                if ($access_token) {

                        //Get data user email address
                        $linkedin_data_email = $this->linkedin_api_lib->get(
                                '/v2/emailAddress?q=members&projection=(elements*(handle~))', 
                                $access_token
                        );

                        $email_user = isset($linkedin_data_email['elements'][0]['handle~']['emailAddress']) 
                                      ? $linkedin_data_email['elements'][0]['handle~']['emailAddress'] 
                                      : "";

                        //Get data user profile
                        $linkedin_user = $this->linkedin_api_lib->get(
                                '/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~digitalmediaAsset:playableStreams))', 
                                $access_token
                        );
                }               
        
                if (!empty($email_user)) {
                        
                        $user_job_seeker = $this->Job_seeker->authenticate_job_seeker_email_address($email_user);

                        //Si no existe se registra el jobseeker
                        if (!isset($user_job_seeker->ID)) {
                                
                                $this->load->library('upload_lib');

                                $locale_first_name = $linkedin_user['firstName']['preferredLocale']['language'] . '_' . $linkedin_user['firstName']['preferredLocale']['country'];
                                $locale_last_name = $linkedin_user['lastName']['preferredLocale']['language'] . '_' . $linkedin_user['lastName']['preferredLocale']['country'];

                                $first_name = $linkedin_user['firstName']['localized'][$locale_first_name];
                                $last_name = $linkedin_user['lastName']['localized'][$locale_last_name];

                                $picture_url = $linkedin_user['profilePicture']['displayImage~']['elements'][1]['identifiers'][0]['identifier'];

                                $birthday = null;
                                $gender = '';

                                $picture_name = $this->Job_seeker->upload_picture_fb_or_linkedin($picture_url);

                                $user_job_seeker = $this->register_job_seeker(
                                        $first_name,
                                        $last_name, 
                                        $email_user, 
                                        $birthday, 
                                        $gender, 
                                        $picture_name
                                );

                                $data_email = array(
                                        'email' => $user_job_seeker->email, 
                                        'password' => $user_job_seeker->password, 
                                        'jobseeker_name' => $user_job_seeker->first_name, 
                                        'social_networks_account' => 'Linkedin'
                                );

                                $config = $this->Email_drafts->email_configuration();
                                $this->email->initialize($config);
                                $this->email->clear(TRUE);
                                $this->email->from(ADMIN_EMAIL, SITE_NAME);
                                $this->email->to($user_job_seeker->email);

                                $mail_message = load_email_view('email/registration_jobseeker_fsn', $data_email);

                                $this->email->subject('Registro por Linkedin');
                                $this->email->message($mail_message);     
                                $this->email->send();

                                $this->session->set_userdata('registration_status', 'in_process');      
                        }

                        if (isset($user_job_seeker->ID)) {

                                if ($user_job_seeker->sts == 'blocked') {
                                        $this->load->view('account_blocked');
                                        return;
                                }
                                
                                $this->Session_job_seeker->create($user_job_seeker);

                                $redirect = ($this->session->userdata('back_from_user_login')) ? $this->session->userdata('back_from_user_login') : 'jobseeker/dashboard';
                                $this->session->set_userdata('back_from_user_login','');
                        
                                redirect(base_url($redirect), '');
                        }
                }
                
                redirect('login');
        }

    private function redirect_fb_login()
        {
                $this->load->library('Facebook/facebook_api_lib');

                $login_fb_url = $this->facebook_api_lib->get_login_url();
                
                header('Location:' . $login_fb_url);

                exit();
        }
        
        public function redirect_linkedin_login()
        {
                $this->load->library('Linkedin/linkedin_api_lib');
                
                $login_linkedin_url = $this->linkedin_api_lib->get_login_url();

                header('Location:' . $login_linkedin_url);

                exit();
        }
}
