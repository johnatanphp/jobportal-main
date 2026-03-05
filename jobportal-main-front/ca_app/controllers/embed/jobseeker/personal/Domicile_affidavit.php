<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Domicile_affidavit extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();

        if (!candidate_is_process_contracting()) {
            //show_404();
        }
        
		//load model
		$this->load->model('Requested_document');
		$this->load->model('Jobseeker_required_document');
        $this->load->model('Jobseeker_form_rtps');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Rys_form_seeker');

        $this->load->model('Seeker_domicile_affidavit');
        $this->load->model('Seeker_declaration_5th_category');

		//Load libraries
		$this->load->library('upload');
        $this->ads = $this->Ad->get_ads();
    }

    public function send($seeker_id)
	{
        $input = $this->input->get();

        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            show_404();
        }

        $present_address = isset($input['present_address']) && trim($input['present_address']) != '-' ? trim($input['present_address']) : '';
        $cia_code = isset($input['cia_code']) ? trim($input['cia_code']) : '';
        $cia_name = isset($input['cia_name']) ? trim($input['cia_name']) : '';
        $cost_center = isset($input['cost_center']) ? trim($input['cost_center']) : '';

        $data['seeker_id'] = $seeker_id;
        $data['seeker'] = $seeker;
        $data['title'] = 'Declaración Jurada Domicilio - ' . SITE_NAME;
        $data['record'] = $this->Seeker_domicile_affidavit->get_latest_for_seeker($seeker_id);
        $data['present_address'] = $present_address;
        $data['cia_code'] = $cia_code; // "02";
        $data['cia_name'] = $cia_name; // "OVERALL STRATEGY S.A.C.";
        $data['cost_center'] = $cost_center; //"02-IN01-SIST-907";

		$this->load->view('embed/jobseeker/personal/send_domicile_affidavit', $data);
	}

    public function send_signature()
    {        
        $seeker_id = $this->input->post('seeker_id');
        $present_address = $this->input->post('present_address');
        $cia_name = $this->input->post('cia_name');
        $cost_center = $this->input->post('cost_center');
        $cia_code = $this->input->post('cia_code');

        $this->load->library(
            'Evicertia_domicile_affidavit_lib', 
            null, 
            'Evicertia_domicile_affidavit_lib'
        );

        $status = $this->Evicertia_domicile_affidavit_lib->send([
            'seeker_id' => $seeker_id,
            'present_address' => $present_address,
            'cia_name' => $cia_name,
            'cia_code' => $cia_code,
            'cost_center' => $cost_center
        ]);

        if ($status) {

            $seeker = $this->Job_seeker->find($seeker_id);
            $seeker_dj = $this->Seeker_domicile_affidavit->get_latest_for_seeker($seeker_id);

            try {
                $this->load->library(
                    'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
                    null, 
                    'Hrm_api_notificacion_cambio_legajo_lib'
                );

                $detail = "<b>Declaración Jurada de domicilio: </b> <a href='" . file_url($seeker_dj->file_path) . "'>Ver</a>";
                
                $this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
                    'titulo' => 'Declaración jurada de domicilio enviada',
                    'detalle' => $detail,
                    'doc_iden_num' => $seeker->document_number
                ]);

            } catch (Exception $e) {}
        }

        echo json_encode([
            'success' => $status
        ]);
    }
}
