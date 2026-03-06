<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sap_connections extends CI_Controller
{
    public function save($company_id = 0)
    {
        $this->load->model('Sap_api_user');

        $data['title'] = SITE_NAME . ': SAP conexión';
        $data['msg'] = '';

        $params = $this->input->post();
        
        $sap_connection = $this->Sap_api_user->find(['company_id' => $company_id]);

        $this->form_validation->set_rules('company_id', 'Compania ID', 'trim|required');
		$this->form_validation->set_rules('api_url', 'API Root URL', 'trim|required');
		$this->form_validation->set_rules('api_username', 'API usuario', 'trim|required');
		$this->form_validation->set_rules('api_password', 'API contrasena', 'trim|required');
		$this->form_validation->set_rules('api_db', 'API base de datos', 'trim|required');
        $this->form_validation->set_rules('active', 'API Estado', 'trim|required|in_list[1,0]');
	    $this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE && count($params) == 0) {
            $data['sap_connection'] = $sap_connection;
            $data['company_id'] = $company_id;
            $this->load->view('admin/sap_connections/common/form_sap_connection', $data);
			return;
		}

        if ($this->form_validation->run() === FALSE && count($params) > 0) {
            echo json_encode([
                'status' => false,
                'message' => 'Por favor ingrese todos los datos requeridos',
            ]);
			return;
		}

        $trans_status = false;

        if ($sap_connection) {
            $this->db->where('company_id', $params['company_id']);
            $trans_status = $this->db->update('tbl_sap_api_users', [
                'api_url' => $params['api_url'],
                'api_username' => $params['api_username'],
                'api_password' => $params['api_password'],
                'api_db' => $params['api_db'],
                'active' => $params['active']
            ]);
        } else {
            $this->db->insert('tbl_sap_api_users', [
                'api_url' => $params['api_url'],
                'api_username' => $params['api_username'],
                'api_password' => $params['api_password'],
                'api_db' => $params['api_db'],
                'active' => $params['active'],
                'company_id' => $params['company_id']
            ]);

            $trans_status = $this->db->insert_id() ? true : false;
        }

        if (!$trans_status) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudieron guardar los datos',
            ]);
			return;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Los datos para la conexión han sido guardados',
        ]);
    }

    public function test() 
    {
        $this->load->library(
            'WS_sap/WS_sap_api_token_lib', 
            null, 
            'WS_sap_api_token_lib'
        );
        
        $params = $this->input->post();
        $sap_api = $this->WS_sap_api_token_lib->generate(null, $params);

        if (!$sap_api) {
            echo json_encode([
                'status' => false,
                'message' => 'Conexión no se pudo realizar, verifique los datos',
            ]);
            return;
        }

        if (isset($sap_api->token) && !empty($sap_api->token)) {
            echo json_encode([
                'status' => true,
                'message' => 'Se ha establecido conexión',
            ]);
            return;
        }
    }
}
