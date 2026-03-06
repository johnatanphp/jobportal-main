<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Contract_document_candidates extends Api_v2_Controller
{
    public function search_get($process_id = null, $candidate_id = null)
    {   
        $this->load->library('Api_services/Api_v2/Candidate_contract_documents/Contract_documents_candidate_search_service');

        $params = [
            'process_id' => $process_id,
            'candidate_id' => $candidate_id
        ];

        list($is_success, $message) = $this->contract_documents_candidate_search_service->validate($params);

        if (!$is_success) {
            $this->response(
                apiv2_response(false, $message),
                self::HTTP_BAD_REQUEST
            );
        }

        $response = $this->response(
            $this->contract_documents_candidate_search_service->exec($params), 
            self::HTTP_OK
        );

        if (!$response['status']) {
            $this->response(
                $response,
                self::HTTP_INTERNAL_SERVER_ERROR
            );
        }
       
        $this->response(
            $response,
            self::HTTP_OK
        );
    }
    
    public function generate_link_post($process_id = null, $candidate_id = null)
    {   
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Recruitment_document_request');
        
        $seeker = $this->Job_seeker->find($candidate_id);

        if (!$seeker) {
            $this->response(
                apiv2_response(false, 'Candidate Id es invalido'),
                self::HTTP_BAD_REQUEST
            );
        }
        
        $process = $this->Recruitment_process->find($process_id);
        
        if (!$process) {
            $this->response(
                apiv2_response(false, 'Proceso Id es invalido'),
                self::HTTP_BAD_REQUEST
            );
        }
        
        $recruitment_candidate = $this->Recruitment_candidate->get_candidate_in_process($process_id, $candidate_id);
        
        if (!$recruitment_candidate) {
            $this->response(
                apiv2_response(false, 'Candidato no esta en el proceso ' . $process_id),
                self::HTTP_BAD_REQUEST
            );
        }
        
        $job_id = $process->job_ID;
        
        $link_url = $this->Recruitment_document_request->create_link($candidate_id, $job_id);

        if (!$link_url) {
            $this->response(
                apiv2_response(false, 'Link de ingreso no pudo ser generado'),
                self::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        $data = [
            'link_url' => $link_url
        ];
          
        $this->response(
            apiv2_response(true, 'Link ha sido generado', $data),
            self::HTTP_OK
        );
    }
}

