<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verification_company extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();

        //Load model
        $this->ads = $this->Ad->get_ads();
    }

    public function activate($token = '')
    {
        $data['title'] = 'Verificación cuenta';
        $data['ads_row'] = $this->ads;

        if (empty($token)) {
            show_404();
            exit();
        }

        $user = $this->db->get_where('tbl_employers', [
            'verification_code' => $token
        ])->row();

        if (!$user || $user->sts != 'pending') {
            show_404();
            exit();
        }

        $user_data = [
            'sts' => 'active',
            'verification_code' => null
        ];

        $this->Employer->update($user->ID, $user_data);
   
        $this->load->view('verification_company', $data);
    }
}
