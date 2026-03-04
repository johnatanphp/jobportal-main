<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employer_signup extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
     
        $this->ads = $this->Ad->get_ads();

        $this->load->model('Employer_profile');
        
        $this->access_code = $this->config->item('employer_signup_token');

        if (!$this->access_code) {
            show_404();
        }

        //die("¡Página está temporalmente desactivada!");   
        $code = $this->input->get('code');
        $code = empty($code) ? $this->input->post('code') : $code;
   
        if ($code !== $this->access_code) {
            show_404(); 
        }

        //show_404(); 
    }
    
    public function index()
    {
        if ($this->session->userdata('user_id')) {
            redirect('login');
            exit;
        }

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Crear cuenta como empleador';
        $data['msg'] = '';
        $data['result_cities'] = $this->City->get_all_cities();
        $data['result_countries'] = $this->Country->get_all_countries();
        $data['result_industries'] = $this->Industry->get_all_industries();
        $data['result_ubigeos'] = $this->Ubigeo->get_all_records();
        
        $this->form_validation->set_rules(
            'email', 
            'Email', 
            'trim|required|valid_email|is_unique[tbl_employers.email]|is_email_business|strip_all_tags', ['is_unique' => 'Email ya está en uso']
        ); 
        $this->form_validation->set_rules('pass', 'Password', 'trim|required|min_length[8]|password_strength');
        $this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required|matches[pass]');
        $this->form_validation->set_rules(
            'company_ruc', 
            'RUC de la empresa', 
            'trim|required|is_unique[tbl_companies.company_ruc]|strip_all_tags', ['is_unique' => 'RUC ya está en uso']
        );
        $this->form_validation->set_rules('full_name', 'Nombre empleador', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('country', 'País', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('city', 'City', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('mobile_phone', 'Mobile', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('company_name', 'Company name', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('industry_id', 'Industry', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('company_location', 'Company address', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('company_description', 'Company Description', 'trim|required|strip_all_tags|secure');
        $this->form_validation->set_rules('company_phone', 'Company Phone', 'trim|strip_all_tags');
        $this->form_validation->set_rules('no_of_employees', 'No of Employees', 'trim|strip_all_tags');
        $this->form_validation->set_rules('company_website', 'Company Website', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'trim|required|valid_grecaptcha');
        
        if (empty($_FILES['company_logo']['name'])) {
            $this->form_validation->set_rules('company_logo', 'Company Logo', 'required');
        }
        
        $this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('employer_signup', $data);
            return;
        }
        
        $current_date_time = date("Y-m-d H:i:s");
        $company_slug = make_slug($this->input->post('company_name'));
        $is_slug = $this->Company->check_slug($company_slug);
        
        if ($is_slug > 0) {
            $company_slug.='-'.time();
        }
        
        $company_phone = '';

        if ($this->input->post('company_phone') != '') {
            $company_phone = $this->input->post('company_phone_code')  . ' ' . $this->input->post('company_phone');
        }

        list($country_id, $country_name) = explode('-', $this->input->post('country'));

        $company_array = [
            'company_ruc' => $this->input->post('company_ruc'),
            'company_name' => $this->input->post('company_name'),
            'industry_ID' => $this->input->post('industry_id'),
            'company_phone' => $company_phone,
            'country_id' => $country_id,
            'company_country' => $country_name,
            'company_city' => $this->input->post('city'),
            'company_location' => $this->input->post('company_location'),
            'company_website' => $this->input->post('company_website'),
            'no_of_employees' => $this->input->post('no_of_employees'),
            'company_description' => $this->input->post('company_description'),
            'company_slug' => $company_slug,
            'ownership_type' => $this->input->post('ownership_type')
        ];

        if (!empty($_FILES['company_logo']['name'])) {
            
            $company_name_for_file = strtolower($this->input->post('company_name'));
            
            $file_name = md5(uniqid(time(), true));
            $path_company_logo = $this->Company->upload_logo('company_logo', $file_name);

            if ($path_company_logo !== false) {
                $company_array['company_logo'] = $path_company_logo;
            }
        }
    
        $company_id = $this->Company->add_company($company_array);

        if (!$company_id) {
            redirect('employer_signup');
            return;
        }
    
        $user_array = [
            'first_name' => $this->input->post('full_name'),
            'email' => $this->input->post('email'),
            'pass_code' => do_hashing($this->input->post('pass')),
            'mobile_phone' => $this->input->post('mobile_phone_code') . ' ' . $this->input->post('mobile_phone'),
            //'home_phone' => $this->input->post('home_phone'),
            'country' => $country_name,
            'city' => $this->input->post('city'),
            //'position' => $this->input->post('position'),
            'ip_address' => $this->input->ip_address(),
            'dated' => $current_date_time,
            'company_ID' => $company_id,
            'sts' => 'pending',
            'verification_code' => create_token(80),
            'top_employer' => 'yes',
            'is_admin' => 'yes'
        ];

        $user_id = $this->Employer->add_user($user_array);

        if (!$user_id) {
            redirect('employer_signup');
            return;
        }

        $user_profile = [
            'user_id' => $user_id,
            'profile_id' => 1
        ];
        $this->Employer_profile->add($user_profile);

        $employer = $this->Employer->find($user_id);
        $company = $this->Company->find($company_id);

        $data_email = [
            'company' => $company, 
            'app_user' => $employer
        ];

        $config = $this->Email_drafts->email_configuration();
        
        //Notificar a usuarios
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($employer->email);

        $mail_message = load_email_view('email/company_registration', $data_email);

        $this->email->subject('Registro cuenta empresa');
        $this->email->message($mail_message);     
        $this->email->send();

        $this->session->set_flashdata(
            'success_msg', 
            '<div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Listo!</strong> 
                Cuenta creada, por favor revisa el correo "' . $employer->email  . '", 
                te llegará un enlace de activación de la cuenta. 
            </div>'
        );
        redirect('employer_signup?code=' . $this->input->get('code'));
    }

    public function search_company()
    {
        $ruc = $this->input->get('ruc');

        $this->load->library(
			'Company/Company_search_info_lib', 
			null , 
			'Company_search_info_lib'
		);

        $company = $this->Company_search_info_lib->search($ruc);

        if (!$company) {
            echo json_encode([
                'success' => false,
                'message' => 'RUC no encontrado'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'ruc' => $company->ruc,
                'name' => $company->name
            ]
        ]);
    }
}
