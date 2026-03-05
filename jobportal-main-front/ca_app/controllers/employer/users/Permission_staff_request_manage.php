<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission_staff_request_manage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        $this->load->model('Employer_profile');
    }

    public function index($user_id = 0)
    {   
        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }

        $data['ads_row'] = $this->ads;
        $data['user'] = $user;
        $data['title'] = 'Gestionar U. Negocios - Solicitudes' ;
        
        $query = $this->db->query("
            SELECT 
                   business_unit_code, 
                   business_unit_name 
            FROM tbl_business_units bu
            WHERE bu.business_unit_code NOT IN(
                SELECT 
                    pbu.business_unit_code 
                FROM tbl_employer_staff_request_manage_business_units pbu 
                WHERE pbu.user_id=$user_id
            ) AND bu.active=1 AND bu.company_id=$user->company_ID"
        );

        $data['business_units'] = $query->result();
        
        $this->load->view('employer/users/permission_staff_request_manage/index', $data);
    }

    public function get_business_units()
    {
        $user = $this->Employer->find($this->input->get('user_id'));

        $this->db->select([
            'bu.business_unit_code',
            'bu.business_unit_name'
        ]);
        $this->db->from('tbl_employer_staff_request_manage_business_units pbu');
        $this->db->join(
            'tbl_business_units bu', 
            'bu.business_unit_code=pbu.business_unit_code'
        );
        $this->db->where('bu.active', 1);
        $this->db->where('bu.company_id', $user->company_ID);
        $this->db->where('pbu.user_id', $user->ID);
        
        $business_units = $this->db->get()->result();

        echo json_encode([
            'data' =>  $business_units
        ]);
    }

    public function save_business_units()
    {
        $user_id = $this->input->post('user_id');

        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }

        $this->db->where('user_id', $user_id);
        $this->db->delete('tbl_employer_staff_request_manage_business_units');

        $business_units = $this->input->post('business_units');
    
        foreach ($business_units as $bu_code) {

            $data = [
                'user_id' => $user_id,
                'business_unit_code' => $bu_code
            ];
            $this->db->insert('tbl_employer_staff_request_manage_business_units', $data);
        }

        $this->session->set_flashdata(
            'success', 
            'Datos han sido guardados'
        );

        redirect('employer/users/permission_staff_request_manage/index/' . $user_id);
    }
}
