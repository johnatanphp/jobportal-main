<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Add_user extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $row_employer = $this->Employer->get_employer_by_id($user_id);
        
        if (!$row_employer) {
            echo 'Illegal Request. Details are sent to administrator.';
            exit;   
        }
        
        if ($this->session->userdata('is_employer') != TRUE){
            echo 'Illegal Request. Details are sent to administrator.';
            exit;   
        }

        $data['ads_row'] = $this->ads;
        $data['title'] =  'Agregar usuario';
        $data['result_countries'] = $this->Country->get_all_countries();

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_employers.email]|strip_all_tags'); 
        $this->form_validation->set_rules('full_name', 'Nombre completo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('country', 'País', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('city', 'Ubicación', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('mobile_phone_code', 'Pefijo teléfonico móvil', 'trim|required|integer');
        $this->form_validation->set_rules('mobile_phone', 'Mobile', 'trim|required|integer');
        $this->form_validation->set_rules('home_phone', 'Teléfono fijo', 'trim|integer');
        
        if ($this->input->post('home_phone') != '') {
            $this->form_validation->set_rules('home_phone_code', 'Prefijo teléfonico fijo', 'trim|required|integer');
        }

        $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

        if ($this->form_validation->run() === FALSE) {

            $this->load->view('employer/users/add_user_view', $data);
            return;
        }

        $current_date_time = date('Y-m-d H:i:s');
        $password = create_random_password();

        $mobile_phone = $this->input->post('mobile_phone_code') . ' '. $this->input->post('mobile_phone');
        $home_phone = '';

        if ($this->input->post('home_phone') != '') {
            $home_phone = $this->input->post('home_phone_code') . ' ' . $this->input->post('home_phone');
        }

        $employer_array = array(
            'first_name' => $this->input->post('full_name'),
            'email' => $this->input->post('email'),
            'pass_code' => do_hashing($password),
            'mobile_phone' => $mobile_phone,
            'home_phone' => $home_phone,
            'country' => $this->input->post('country'),
            'city' => $this->input->post('city'),
            'position' => $this->input->post('position'),
            'ip_address' => $this->input->ip_address(),
            'dated' => $current_date_time,
            'company_ID' => $row_employer->company_ID,
        );

        $app_user_id = $this->Employer->add_user($employer_array);
    
        if ($app_user_id) {
            
            $this->db->insert('tbl_profile_actions_permissions', [
                'profile_id' => '2', //Perfil solicitante
                'action_id' => '5', //Accion crear solicitud externa
                'employer_id' => $app_user_id
            ]);
            
            $data_email = array(

                'username' => $this->input->post('email'),
                'password' => $password,
                'employer_name' => $this->input->post('full_name'),
                'url_jp_login' => base_url('login')
            );

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($this->input->post('email'));
            
            $mail_message = load_email_view('email/new_employer', $data_email);
        
            $this->email->subject('Cuenta creada');
            $this->email->message($mail_message);     
            $this->email->send();
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Cuenta creada!</strong> Se ha enviado un correo a <b>' . $this->input->post('email') . '</b> con los accesos de la nueva cuenta del usuario <b>' . $this->input->post('full_name') . '</b>. </div>');
            redirect('employer/users/profiles/index/' . $app_user_id);

            exit;           
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> Ocurrió un error al tratar de guardar los datos. </div>');
        }
    }
}
