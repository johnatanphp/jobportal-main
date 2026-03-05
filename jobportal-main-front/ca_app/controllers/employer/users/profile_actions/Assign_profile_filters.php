<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assign_profile_filters extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        //Load model
        $this->load->model('Business_unit');
    }

    public function index($user_id)
    {
        $data['title'] = 'Perfil del solicitante';
        $data['ads_row'] = $this->ads;

        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }
        
        $data['app_user_info'] = $user;
        $data['recruiter_info'] = $user;

        $data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $user->company_ID]);
        $data['client_companies'] = $this->Employer->get_client_companies($user->ID);

        $this->load->view('employer/users/profile_actions/assign_profile_filters', $data);
    }

    public function business_unit_allowed($user_id)
    {
        $user = $this->Employer->find($user_id);
        $businessUnits = $this->Employer->get_allowed_business_units($user);
        
        echo json_encode($businessUnits);
    }

    public function save_business_units($user_id)
    {
        $user = $this->Employer->find($user_id);
        
        if (!$user) {
            show_404();
        }

        $data_input = $this->input->post();

        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->where('employer_id', $user_id);
        $this->db->delete('tbl_employer_permission_business_units');

        if (isset($data_input['business_unit_codes'])) {
            foreach ($data_input['business_unit_codes'] as $business_unit_code) {
                $data_to_insert = [
                    'business_unit_code' => $business_unit_code,
                    'employer_id' => $user_id,
                    'created_at' => $data['created_at']
                ];
                $this->db->insert('tbl_employer_permission_business_units', $data_to_insert);
            }
        }
        
        $this->db->where('employer_id', $user_id);
        
        if (isset($data_input['business_unit_codes']) && !empty($data_input['business_unit_codes'])) {
            $this->db->where_not_in('business_unit_code', $data_input['business_unit_codes']);
        }
        
        $this->db->delete('tbl_employer_permission_cost_centers');
        
        $this->session->set_flashdata('success', 'Registros actualizados');

        echo json_encode([
            'status'  => 'success',
            'message' => '¡Registros actualizados!'
        ]);
        exit;
    }

    public function save_consultants($user_id)
    {
            $user = $this->Employer->find($user_id);
            
            if (!$user) {
                show_404();
            }

            $data_input = $this->input->post();

            $data['created_at'] = date('Y-m-d H:i:s');

            $this->db->where('employer_id', $user_id);
            $this->db->delete('tbl_employer_permission_consultants');

            if (isset($data_input['consultant_codes'])) {
                foreach ($data_input['consultant_codes'] as $consultant_code) {
                    $data_to_insert = [
                        'consultant_code' => $consultant_code,
                        'employer_id' => $user_id,
                        'created_at' => $data['created_at']
                    ];
                    $this->db->insert('tbl_employer_permission_consultants', $data_to_insert);
                }
            }
            
            // Eliminar permisos clientes y CECO de las consultoras que no tengan permisos
            $this->db->where('employer_id', $user_id);
            if (isset($data_input['consultant_codes'])) {
                $this->db->where_not_in('consultant_code', $data_input['consultant_codes']);
            }
            $this->db->delete('tbl_employer_permission_clients');
            
            $this->db->where('employer_id', $user_id);
            if (isset($data_input['consultant_codes'])) {
                $this->db->where_not_in('consultant_code', $data_input['consultant_codes']);
            }
            $this->db->delete('tbl_employer_permission_cost_centers');
            
            $this->session->set_flashdata('success', 'Registros actualizados');
            
            echo json_encode([
                'status'  => 'success',
                'message' => '¡Registros actualizados!'
            ]);
            exit;
    }

    public function consultants_allowed($user_id)
    {
        $user = $this->Employer->find($user_id);
        $consultans = $this->Employer->get_allowed_user_consultants($user);

        echo json_encode($consultans);
    }

    public function save_clients($user_id)
    {
        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }

        $data_input = $this->input->post();

        $this->db->where('employer_id', $user_id);
        $this->db->delete('tbl_employer_permission_clients');

        if (!empty($data_input['client_codes'])) {
            foreach ($data_input['client_codes'] as $row) {
                list($consultant_code, $client_code) = explode('__', $row);
                
                $data_to_insert = [
                    'consultant_code' => $consultant_code,
                    'client_code' => $client_code,
                    'employer_id' => $user_id,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->db->insert('tbl_employer_permission_clients', $data_to_insert);
            }
        
        }
    
        if (!empty($data_input['client_codes'])) {
            $this->db->where_not_in("CONCAT(consultant_code, '__', client_code)", $data_input['client_codes']);
        }
        
        $this->db->where('employer_id', $user_id);
        $this->db->delete('tbl_employer_permission_cost_centers');
        
        $this->session->set_flashdata('success', 'Registros actualizados');
        
        echo json_encode([
            'status'  => 'success',
            'message' => '¡Registros actualizados!'
        ]);
        exit;
    }

    public function clients_allowed($user_id)
    {
        $data_input = $this->input->post();

        $user = $this->Employer->find($user_id);
        $clients = $this->Employer->get_allowed_user_clients($user, $data_input['consultant_codes']);

        echo json_encode($clients);
    }

    public function save_cost_centers($user_id)
    {
        $user = $this->Employer->find($user_id);
            
        if (!$user) {
            show_404();
        }

        $data_input = $this->input->post();

        $this->db->where('employer_id', $user_id);
        $this->db->delete('tbl_employer_permission_cost_centers');

        if (isset($data_input['cost_center_codes'])) {
            foreach ($data_input['cost_center_codes'] as $data) {
                $data_to_insert = [
                    'consultant_code' => $data['consultant_code'],
                    'client_code' => $data['client_code'],
                    'business_unit_code' => $data['business_unit_code'],
                    'cost_center_code' => $data['cost_center_code'],
                    'employer_id' => $user_id,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('tbl_employer_permission_cost_centers', $data_to_insert);
            }
        }
        
        $this->session->set_flashdata('success', 'Registros actualizados');
        
        echo json_encode([
            'status'  => 'success',
            'message' => '¡Process complete!'
        ]);
        exit;
    }

    public function cost_centers_allowed($user_id)
    {
        $data_input = $this->input->post();
        $user = $this->Employer->find($user_id);
        $consultans = $this->Employer->get_allowed_user_cost_centers($user);

        echo json_encode($consultans);
    }

    public function business_units_all($user_id)
    {
        $user = $this->Employer->find($user_id);
        $businessUnits = $this->Employer->get_all_business_units($user->company_ID);
        echo json_encode($businessUnits);
        exit;
    }

    public function consultants_all($user_id)
    {
        $user = $this->Employer->find($user_id);
        $consultants = $this->Employer->get_all_consultants($user);
        echo json_encode($consultants);
        exit;
    }

    public function clients_all($user_id)
    {
        $data_input = $this->input->post();
        $user = $this->Employer->find($user_id);
        $clients = $this->Employer->get_all_clients($user, $data_input['consultant_codes']);
        echo json_encode($clients);
        exit;
    }

    public function cost_centers_all($user_id)
    {
        $data_input = $this->input->post();
        $user = $this->Employer->find($user_id);
        $clients = $this->Employer->get_all_cost_centers($user, $data_input['client_codes'] ?? [], $data_input['business_unit_codes'] ?? []);
        echo json_encode($clients);
        exit;
    }
}