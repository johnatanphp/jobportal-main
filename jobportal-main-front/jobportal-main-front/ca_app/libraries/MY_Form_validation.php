<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation {
	
    public function valid_url($str){
        
        if (filter_var($str, FILTER_VALIDATE_URL)) {
            return TRUE;
        }
        
        return FALSE;
    }

	public function validate_ml_spam($captcha) {
        $CI = & get_instance();
        $CI->form_validation->set_message('validate_ml_spam', 'Código de verificación ingresado inválido.');
        $spam_validation = true;
        $post_captcha_value = $captcha;
        $original_captcha_value = $CI->session->userdata('cptcode');
        if (strtolower($post_captcha_value) != strtolower($original_captcha_value)) {
            $CI->session->userdata['cptcode'] = 'ans951753qwerttyhdehb15hdtyhjk95412ytfvyhbhsqwertty1485';
            $spam_validation = false;
        }
        return $spam_validation;
    }
    public function time_diff($str) {
        $CI = & get_instance();
        $CI->form_validation->set_message('time_diff', 'No puedo enviar correos con demasiada frecuencia.');
        $spam_validation = true;
        $timestm = $CI->session->userdata('timestm');
        if (isset($timestm) && !empty($timestm)) {
            $mailtime = eventtime($CI->session->userdata('timestm'), 5);
            //echo $mailtime ;exit;
            if ($mailtime == 'false') {
                $spam_validation = false;
            }
        }
        return $spam_validation;
    }
    public function file_required($str) {
        if (!is_array($str)) {
            return (trim($_FILE[$str]) == '') ? FALSE : TRUE;
        } else {
            return (!empty($_FILE[$str]));
        }
    }
	
	public function secure($str) {
        $CI = & get_instance();
		$prohibited_keywords = array();//$CI->prohibited_keywords_model->get_all_records();
        $validation = true;
        /*
        foreach ($prohibited_keywords as $prohibited_keyword) {
			$kw = $prohibited_keyword->keyword;
            $kw1 = "/" . $kw . "/i";
            if (preg_match($kw1, $str)) {
				$validation = false;
				break;
            }
        }		
		$CI->form_validation->set_message('secure', '"'.$kw.'" can\'t be used');
        */
        return $validation;				
    }
	
	public function strip_all_tags($str) {
        $newstr = strip_tags($str);
        return $newstr;				
    }
	
	public function secure_banned($str) {
        $CI = & get_instance();
        $CI->form_validation->set_message('secure_banned', 'Datos inválidos');
		$banned_keywords = $CI->Banned_keywords->get_keywords();
        $validation = true;
        foreach ($banned_keywords as $banned_keyword) {
			$kw = $banned_keyword->keyword;
            $kw1 = "/" . $kw . "/i";
            if (preg_match($kw1, $str)) {
				
				$replace_with = '<span style="text-decoration:underline; font-weight:bold;">'.$kw.'</span>';
				$str = str_replace($kw, $replace_with, $str);
				
				$user_name = $CI->session->userdata('member_user_name');
				$full_name = $CI->session->userdata('member_first_name');
				
				if($user_name != ''){
					$config = array();
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
		
					$CI->email->initialize($config);
		
					$CI->email->from('pr@pkmotors.com', 'Banned Keyword');
					$CI->email->reply_to('pr@pkmotors.com', 'Banned Keyword');
					$CI->email->to('pr@pkmotors.com');
					$CI->email->subject('Banned keyword ("'.$kw.'") used at pkmotors.com');
		
					$mail_message = $CI->Email_drafts->get_banned_keyword_draft($full_name, $user_name, $str);
					$CI->email->message($mail_message);
					$CI->email->send();
					
					$member_id = $CI->Member->get_member_id_by_user_name($user_name);
					$CI->Member->block_registered_user($member_id, 1, 2);
				}
                $validation = false;
				break;
            }
        }
        return $validation;
    }
	
	public function phone($str) {
        $CI = & get_instance();
        if ($str == '( XXX )XXX XXXX' or $str == '') {
            $CI->form_validation->set_message('phone', '%s es requerido');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function in_list_db($str, $field)
    {
        $CI = & get_instance();

        sscanf($field, '%[^.].%[^.]', $table, $field);
        
        $row = $CI->db->limit(1)->get_where($table, array($field => $str))->row_array();

        if (!(isset($row[$field]) && $row[$field] == $str)) { 
            $CI->form_validation->set_message('in_list_db', 'EL campo {field} tiene un valor incorrecto');
            return FALSE;
        }
        
        return TRUE;
    }

    public function valid_date($date)
    {
        $CI = & get_instance();

        $date = str_replace('/', '-', $date);

        if (strtotime($date) === false) {
            $CI->form_validation->set_message('valid_date', 'La fecha del campo {field} es inválida');
            return FALSE;
        }
        
        return TRUE;
    }

    public function date_greater_than_equal_to($date, $date_compare)
    {
        $CI = & get_instance();

        $date = str_replace('/', '-', $date);
        $date_compare = str_replace('/', '-', $date_compare);
        
        if (strtotime($date) < strtotime($date_compare)) {

            $CI->form_validation->set_message('date_greater_than_equal_to', 'La fecha del campo {field} debe ser mayor o igual a la fecha ' . $date_compare);
            return FALSE;
        }

        return TRUE;
    }

    public function valid_grecaptcha($recaptcha_response)
    {
        $CI = & get_instance();

        $CI->load->library('recaptcha');
        
        $key_secret = $CI->config->item('google_recaptcha_api_key_secret');

        $response = $CI->recaptcha->verify($recaptcha_response, $key_secret);
         if (isset($response['success']) && $response['success'] === true) {
            return TRUE;
        }
      
        $CI->form_validation->set_message('valid_grecaptcha', 'reCaptcha inválida');
        return FALSE;
    }

    public function valid_grecaptcha_invisible($recaptcha_response)
    {
        $CI = & get_instance();

        $CI->load->library('recaptcha');

        $key_secret = $CI->config->item('google_recaptcha_api_invisible_key_secret');
        
        $response = $CI->recaptcha->verify($recaptcha_response, $key_secret);
    
        if (isset($response['success']) && $response['success'] === true) {
            return TRUE;
        }
        
        $CI->form_validation->set_message('valid_grecaptcha_invisible', 'reCaptcha inválida');
        
        return FALSE;
    }
    
    public function edit_is_unique($str, $field)
    {   
        $CI = & get_instance();

        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);
        $CI->form_validation->set_message('edit_is_unique', 'El valor de este campo ya está en uso');
        return isset($this->CI->db) ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, 'id !=' => $id))->num_rows() === 0) : FALSE;
    }

    public function is_email_business($email)
    {
        $CI = & get_instance();

        $parts = explode('@', $email);
        $domain_parts = explode('.', $parts[1]);

        $free_emails = [
            'gmail',
            'hotmail',
            'outlook',
            'yahoo'
        ];

        if (in_array($domain_parts[0], $free_emails) == false) {
            return TRUE;
        }

        $CI->form_validation->set_message('is_email_business', 'Correo ingresado debe ser empresarial');
        
        return FALSE;
    }

    public function valid_ruc_company($ruc)
    {
        $CI = &get_instance();

        $CI->load->library(
			'Company/Company_search_info_lib', 
			null , 
			'Company_search_info_lib'
		);

        $company = $CI->Company_search_info_lib->search($ruc);

        if ($company && $company->ruc) {
            return TRUE;
        }

        $CI->form_validation->set_message('valid_ruc_company', 'No se pudo verificar el RUC');

        return FALSE;
    }

    public function rys_doc_allowed_upload($document_key)
    {
        $doc_allowed = rys_documents_allowed_to_upload();

        if (in_array($document_key, $doc_allowed)) {
            return TRUE;
        }

        $CI->form_validation->set_message('rys_doc_allowed_upload', "Documento no puede ser cargado");

        return FALSE;
    }

    public function password_strength($password)
    {
        $CI = &get_instance();

        if (is_password_strength($password)) {
            return TRUE;
        }

        $CI->form_validation->set_message(
            'password_strength', 
            'Contraseña debe tener minúculas, mayúculas, números y caracteres especiales.'
        );
        
        return FALSE;
    }

    public function is_date_format($str_date, $format)
    {
        $CI = &get_instance();

        $date = new DateTime($str_date);
        
        if ($date->format($format) == $str_date) {
            return TRUE;
        }

        $CI->form_validation->set_message('is_date_format', "La fecha no tiene el formato correcto");

        return FALSE;
    }

    public function is_valid_phone_number($str)
    {
        $CI = & get_instance();

        $patron = '/^\+?\d+$/';
       
        if (!preg_match($patron, $str)) {
            $CI->form_validation->set_message('is_valid_phone_number', '%s no es válido');
            return false;
        }

        try {
            $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

            $phoneNumberObject = $phoneNumberUtil->parse($str, null);

            $is_valid_number = $phoneNumberUtil->isValidNumber($phoneNumberObject);

            if ($is_valid_number == true) {
                return true;
            }
        } catch (\libphonenumber\NumberParseException $e) {}
        
        $CI->form_validation->set_message('is_valid_phone_number', '%s no es válido');
        return false;
    }
} 
