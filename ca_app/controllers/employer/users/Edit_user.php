<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_user extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();
    }
    
    public function change_status($employer_id)
    {
        $user_id = $this->session->userdata('user_id');

        if (!$user_id) {
            echo 'Your session has been expired, please re-login first.';
            exit;   
        }
        
        if ($this->session->userdata('is_employer') != TRUE) {
            echo 'Illegal Request. Details are sent to administrator.';
            exit;   
        }
        
        $row_employer = $this->Employer->get_employer_by_id($employer_id);
        
        if (!$row_employer || $row_employer->sts == 'pending') {
            echo 'It seems an illegal Request. Details are sent to administrator.';
            exit;
        }
    
        $new_status = $row_employer->sts == 'active' ? 'blocked' : 'active';

        $data = array ('sts' => $new_status);
        
        $this->Employer->update_employer($employer_id, $data);
        echo $new_status;
    }   
}
